# myLuisella-server

### Required
Having a server with Apache2, MySQL and PHP5 installed.
### Installation and usage
Clone this repository and copy it to your server DocumentRoot (default /var/www):

      git clone https://www.github.com/myLuisella-server.git
      cp -r ./myLuisella-server/ /var/www/

Open mysql utility and import ```luisella.sql```.

Create ```globals.php``` and paste these lines:

      $DB_host = HOST;
      $DB_name = DB_NAME;
      $DB_user = USER;
      $DB_pass = PASSWORD;

Edit values based on your credentials to access your database.

Now you're done!
