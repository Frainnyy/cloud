
# EC2 Instances
resource "aws_instance" "readnet_instance" {
  count                  = 2                       # สร้าง EC2 ในแต่ละ Availability Zone
  ami                    = "ami-0c02fb55956c7d316" # AMI สำหรับ Amazon Linux 2 ใน us-east-1
  instance_type          = "t3.micro"
  key_name               = "vockey"                                             # ใส่ชื่อของ SSH Key Pair ที่คุณต้องการใช้
  subnet_id              = element(aws_subnet.public_subnets.*.id, count.index) # เลือก subnet ที่ต่างกันในแต่ละ AZ
  vpc_security_group_ids = [aws_security_group.inventory_app_sg.id]             # ใช้ Security Group ที่กำหนดไว้ (ALBSG)



  tags = merge(
    local.common_tags,
    {
      Name = "${local.resource_prefix}-EC2-Instance-${count.index}"
    }
  )
}
