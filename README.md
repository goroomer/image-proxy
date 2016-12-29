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

![alt text](https://github.com/goroomer/image-proxy/raw/master/src/docs/diagram.png "Response time(ms)")

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
Setup the EC2
------


TODO
======
