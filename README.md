# PHP Gallery Site
## About
This site was made by programmer who doesn't know PHP (no matter why it was made). This is the copy from old github account, somewhere flushed and more clean (e.g. bootstrap dist folder was removed). Gallery doesn't use any PHP frameworks and may work not properly (e.g. I suspect problems in login system) but I will not fix them, no one of them. But you can distribute this project and use your's commits (which I will check and aplly) as some plus in your carma or resume.

## Installation
Follow instruction below the topic and all be okay. This site is not for docker or any fresh packing \ unpacking systems this is just two weeks example of how somebody can create a site without any frameworks.

### Requirements
#### PHP >=8.0 web server or analog
Indicated `php.ini` file changes:
* `[703]` post_max_size = >3M (8M by default)
* `[846]` file_uploads = On (On by default)
* `[855]` upload_max_filesize = 3M (2M by default)
* `[858]` max_file_uploads = 1 (20 by default)
* `[944]` uncomment postgres driver extension (by default comment)

#### PostgreSQL equal or newer then `PostgreSQL 13.2, compiled by Visual C++ build 1914, 64-bit`
`config/postgresql.ini` with:
* `host=host_adrress`
* `port=host_ip`
* `database=name`
* `user=PHP`
* `password=password`

For table initialization run `src/DBINIT.php` with commented 9 line (there is throw exception for insurance).

#### Bootstrap 5.0.2 or above in public dists folder

### Linux / Mac / Windows
On Windows you can use `start.bat` for testing the site. For any OS you can use this or equal command line: `php -S localhost:8000 -t public/`. Note that if you need test the site with several users you must change the ip (e.g. use `0.0.0.0:8000`) and allow to outer connection in the firewall.

## Description
Gallery is a test site which represents an example of gallery type site (e.g. Pinterest).
