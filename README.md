# Instagram42

### Purpose
```
Create an Instagram like from scratch without any modules or framework.

Add the possibility to take a picture from the camera and add filter on it
```

### Restriction
```
The backend should be in PHP

The frontend in HTML CSS and vanilla JS (plain JavaScript without any additional libraries like jQuery).

The database has to be in sql language
```

### Result

<p align='center'><img src="https://media.giphy.com/media/25R4xK8Z8m3SzWOvo8/giphy.gif" alt='instagram42'/></p>
<p align='center'><i>Home + Profile accessible when you're not connected</i></p>
<br/>
<br/>
<p align='center'><img src="https://media.giphy.com/media/1AjVkD6Akvqa44GQqY/giphy.gif" alt='instagram42'/></p>
<p align='center'><i>Like + Comment post when you're connected</i></p>
<br/>
<br/>
<p align='center'><img src="https://media.giphy.com/media/WvuqZ64IcDw4Wed5oQ/giphy.gif" alt='instagram42'/></p>
<p align='center'><i>Notifications + display comments and likes in the home</i></p>
<br/>
<br/>
<p align='center'><img src="https://media.giphy.com/media/3fdDSYp26ucsyD4d8U/giphy.gif" alt='instagram42'/></p>
<p align='center'><i>Change profile picture + take picture, add filter and post it</i></p>
<br/>

### Installation

Install the project on your computer (open your terminal then paste this) :
```
git clone https://github.com/unicolai42/Instagram42.git Instagram42
```

Install mysql and open it :
```
brew install mysql

mysql -u [your username] -p
```

Create a database and close mysql:
```
CREATE DATABASE Instagram42;

quit;
```

Download the database and launch the server:
```
cd config

./setup.php

../ (go to the root of the project)

php -S localhost:8080 (if php isn't install tape this before -> brew install php)
```

Open the project in your Navigator :
```
http://localhost:8080 (Paste this in the url)
```
