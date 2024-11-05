# RDS Subnet Group
resource "aws_db_subnet_group" "rds_subnet_group" {
  name        = "readnet_db_subnet_group"
  description = "Subnet group for ReadNet RDS instance"
  subnet_ids = [
    aws_subnet.private_db_subnets[0].id,
    aws_subnet.private_db_subnets[1].id
  ]

  tags = merge(
    local.common_tags,
    {
      Name = "${local.resource_prefix}-RDS-SubnetGroup"
    }
  )
}

# RDS Instance
resource "aws_db_instance" "readnet_rds" {
  identifier             = "readnet-db"
  engine                 = "mysql"
  engine_version         = "8.0"
  instance_class         = "db.t3.micro" # เลือก burstable instance class
  allocated_storage      = 20
  max_allocated_storage  = 100
  db_subnet_group_name   = aws_db_subnet_group.rds_subnet_group.name
  vpc_security_group_ids = [aws_security_group.example_db_sg.id]
  multi_az               = true
  storage_type           = "gp2"
  publicly_accessible    = false
  db_name                = "countries"

  username = "admin"        # ระบุชื่อผู้ใช้ของฐานข้อมูล
  password = "Password123!" # ระบุรหัสผ่านที่แข็งแรง

  skip_final_snapshot = true  # ไม่ต้องการทำการ back up เมื่อทำการลบ
  deletion_protection = false # ปิดการป้องกันการลบเพื่อให้สะดวกในการทดสอบ

  tags = merge(
    local.common_tags,
    {
      Name = "${local.resource_prefix}-RDS"
    }
  )
}
