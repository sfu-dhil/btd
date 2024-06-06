.. _config:

Configuration
=============

Most of the application is configured with the symfony parameters.yml
file in app/config/parameters.yml.

.. code-block:: yaml
   
   parameters:
     # database configuration
     database_host: 127.0.0.1
     database_port: null
     database_name: btd
     database_user: btd
     database_password: abc123

     # mailer configuration
     mailer_transport: smtp
     mailer_host: 127.0.0.1
     mailer_user: null
     mailer_password: null

     # A secret key that's used to generate certain security-related tokens
     secret: d2d31e391ebe8e7f307f64f1348d2c84b030cc66

     # Router and cookie information
     router.request_context.scheme: http
     router.request_context.host: example.com
     router.request_context.base_url: /path/to/application

     # words in a generated excerpt
     nines_blog.excerpt_length: 50

     # number of posts to show on the home page
     nines_blog.homepage_posts: 3

     # number of posts to show in the drop down menu.
     nines_blog.menu_posts: 5

     # path to store the upload files.
     btd.media_upload_path: '%kernel.root_dir%/data/uploads'

     # size of the generated thumbnails.
     btd.media_thumbnail_size: 256

.. todo::

   Does the btd.media_thumbnail_size parameter actually get used anywhere?
