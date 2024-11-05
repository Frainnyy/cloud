
# Security Group: ALBSG
resource "aws_security_group" "alb_sg" {
  vpc_id      = "vpc-0ef59aca1a63aa7e8"
  name        = "ALBSG"
  description = "Port 80"

  ingress {
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

}

# Security Group: Inventory-App
resource "aws_security_group" "inventory_app_sg" {
  vpc_id      = "vpc-0ef59aca1a63aa7e8"
  name        = "Inventory-App"
  description = "Enable access to App"

  ingress {
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
  # Allow SSH access on port 22
  ingress {
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
  ingress {
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

}

# Security Group: ExampleDB-SG
resource "aws_security_group" "example_db_sg" {
  vpc_id      = "vpc-0ef59aca1a63aa7e8"
  name        = "ExampleDB-SG"
  description = "Enable access to MySQL"

  ingress {
    from_port       = 3306
    to_port         = 3306
    protocol        = "tcp"
    security_groups = [aws_security_group.inventory_app_sg.id]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

}
