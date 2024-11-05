# VPC
resource "aws_vpc" "ReadNetVPC" {
  cidr_block           = var.network_address_space
  enable_dns_support   = true
  enable_dns_hostnames = true
  tags = merge(
    local.common_tags,
    {
      Name = "${local.resource_prefix}-VPC"
    }
  )
}

# Internet Gateway
resource "aws_internet_gateway" "igw" {
  vpc_id = aws_vpc.ReadNetVPC.id
  tags = merge(
    local.common_tags,
    {
      Name = "${local.resource_prefix}-InternetGateway"
    }
  )
}

# NAT Gateway (one for each AZ)
resource "aws_eip" "nat_eip" {
  count = var.subnet_count
  vpc   = true
}

resource "aws_nat_gateway" "nat_gw" {
  count         = var.subnet_count
  allocation_id = aws_eip.nat_eip[count.index].id
  subnet_id     = aws_subnet.public_subnets[count.index].id
  tags = merge(
    local.common_tags,
    {
      Name = "${local.resource_prefix}-NATGateway-${count.index}"
    }
  )
}

# Subnets
resource "aws_subnet" "public_subnets" {
  count                   = var.subnet_count
  vpc_id                  = aws_vpc.ReadNetVPC.id
  cidr_block              = cidrsubnet(var.network_address_space, 8, count.index)
  map_public_ip_on_launch = true
  availability_zone       = var.availability_zones[count.index]
  tags = merge(
    local.common_tags,
    {
      Name = "${local.resource_prefix}-PublicSubnet-${count.index}"
    }
  )
}

resource "aws_subnet" "private_subnets" {
  count             = var.subnet_count
  vpc_id            = aws_vpc.ReadNetVPC.id
  cidr_block        = cidrsubnet(var.network_address_space, 8, count.index + 2)
  availability_zone = var.availability_zones[count.index]
  tags = merge(
    local.common_tags,
    {
      Name = "${local.resource_prefix}-PrivateSubnet-${count.index}"
    }
  )
}

resource "aws_subnet" "private_db_subnets" {
  count             = var.subnet_count
  vpc_id            = aws_vpc.ReadNetVPC.id
  cidr_block        = cidrsubnet(var.network_address_space, 8, count.index + 4)
  availability_zone = var.availability_zones[count.index]
  tags = merge(
    local.common_tags,
    {
      Name = "${local.resource_prefix}-PrivateDBSubnet-${count.index}"
    }
  )
}

# Route Tables
## Public Route Table
resource "aws_route_table" "public_rt" {
  vpc_id = aws_vpc.ReadNetVPC.id
  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.igw.id
  }
  tags = merge(
    local.common_tags,
    {
      Name = "${local.resource_prefix}-PublicRouteTable"
    }
  )
}

## Private Route Table
resource "aws_route_table" "private_rt" {
  vpc_id = aws_vpc.ReadNetVPC.id
  tags = merge(
    local.common_tags,
    {
      Name = "${local.resource_prefix}-PrivateRouteTable"
    }
  )
}

resource "aws_route" "private_nat_route" {
  count                  = var.subnet_count
  route_table_id         = aws_route_table.private_rt.id
  destination_cidr_block = "0.0.0.0/0"
  nat_gateway_id         = aws_nat_gateway.nat_gw[count.index].id
  depends_on             = [aws_nat_gateway.nat_gw]
}

## Private DB Route Table
resource "aws_route_table" "private_db_rt" {
  vpc_id = aws_vpc.ReadNetVPC.id
  tags = merge(
    local.common_tags,
    {
      Name = "${local.resource_prefix}-PrivateDBRouteTable"
    }
  )
}

# Route Table Associations
## Public Subnet Route Table Association
resource "aws_route_table_association" "public_subnet_association" {
  count          = var.subnet_count
  subnet_id      = aws_subnet.public_subnets[count.index].id
  route_table_id = aws_route_table.public_rt.id
}

## Private Subnet Route Table Association
resource "aws_route_table_association" "private_subnet_association" {
  count          = var.subnet_count
  subnet_id      = aws_subnet.private_subnets[count.index].id
  route_table_id = aws_route_table.private_rt.id
}

## Private DB Subnet Route Table Association
resource "aws_route_table_association" "private_db_subnet_association" {
  count          = var.subnet_count
  subnet_id      = aws_subnet.private_db_subnets[count.index].id
  route_table_id = aws_route_table.private_db_rt.id
}
