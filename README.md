Overview
======
This little project was built to answare our need for a simple image proxy solution.
It is very light, with no frameworks to slow down the request.

Deployment
======
![alt text](https://github.com/goroomer/image-proxy/raw/master/src/docs/diagram.png "Solution implementation")


Performance
======
Response time
------
A 3MB file request:
- Check for cached version
- Get source CloudFront
- Fit: 350x350px
- Our CloudFront
- User
the average results under 500ms (95% of which is getting the image from source)

![alt text](https://github.com/goroomer/image-proxy/raw/master/src/docs/avg_latency.PNG "Response time(ms)")

CPU Load
------
With 3 t2.nano instances we utilize less then 10% cpu for 350K requests per day

Usage
======
     https://static.example.com/param_string/source_url
param_string
------
| Function       | String       |
| ------------- |:-------------:|
| Fit the original image to the given size     | fit:{width}x{height} |
| Set width      | w:{width}    |
| Set width & keep aspect ratio      | w:{width}c    |
| Set height      | h:{height}    |
| Set height & keep aspect ratio     | h:{height}c   |
| Crop  to given size (centered)    | c:{width}x{height}    |
| Set quality   | q:{percent}   |

Examples
------
Best fit to a 250x250 box, 100% quality
              
    https://static.example.com/fit:250x250,q:100/source_url
    
Crop to 250x250 box, 50% quality
              
    https://static.example.com/c:250x250,q:50/source_url
    
Set width to 250px and keep the height as is
              
    https://static.example.com/w:250/source_url
    
Set height to 250px and keep aspect ratio
              
    https://static.example.com/h:250c/source_url

Installation
======

The installation is really straight forward, you can use chef / docker if you know how.
or skip the overhead and deploy a plain installation with ec2 images and basic bash scripts 

Create a working image proxy 
------
- Setup an EC2 instance on which you'll create the basic settings
- Install php7.0, nginx & git on the EC2

         sudo add-apt-repository ppa:ondrej/php
         sudo apt-get update
         sudo apt install nginx php-fpm php-gd git
 
- Clone this repository to your folder of choice (i.e /home/image-proxy/)

         cd /home
         git clone https://github.com/goroomer/image-proxy.git
         
- Create a virtual host with your domain static.yourdomain.com , set root to src folder (i.e /home/image-proxy/src)
- Set thees redirect rules


    location / {
            index index.php;
            rewrite ^/check^ / last; 
            rewrite ^/(.*)/(.*\:.*) /?source_url=$2&params=$1 last; 
    }
    
##### At this point you have a working proxy

Create a redundant AWS auto scaling deployment
------
- Save the EC2 instance as an AMI
- Create VPC with at lease 2 availability zones (for redundancy)
- Create launch configurations for the AMI and VPC
- Create an Auto Scaling Group for the Launch Configuration
- Create Scaling policies
- Create Target Group under LOAD BALANCING in the EC2 tab (set health check to path: /check )
- Create a Load Balancer with the Target Group
- Set your DNS for static.yourdoamin.com to the Load Balancer CNAME record
- Test your setup go to: static.yourdomain.com/fit:500x500,q:90/https://static.pexels.com/photos/211929/pexels-photo-211929.jpeg
- OPTIONAL: You can add a CDN to this setup: create a CloudFront distribution connected to your load balancer, and set you DNS to the CloudFront distribution CNAME


TODO
======
- Create an installation script
- Create sample nginx & php conf files
- Add some security features to the code
- Add Redis cache adapter


Thanks
======
- Oliver Vogel, for his intervention/image library http://image.intervention.io/