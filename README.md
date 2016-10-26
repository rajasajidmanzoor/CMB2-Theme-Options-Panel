# CMB2 Theme Options Panel
### Object Oriented way of using CMB2 to make a custom Theme Options panel.

**Complete Theme Options Panel developed using CMB2. - Easy to integrate and extend**

Anyone can use this theme options panel in their projects. It support all fields types of CMB2.

#### How to USe
First of all clone this repository and place in your theme directory.
Then add following line in functions.php 

    require_once dirname( __FILE__ ) . '/custom_posts.php'; 

Once you have done this step you can see Theme options Menu in your Admin dashboard. 
Now open file custom_posts.php and customize this according to your needs.

To your theme options values in frontend you can use following examples

     global $myTheme_options;
     $myTheme_options=get_site_option( 'options-page', true, false) ;
     $site_title=myTheme_options['site_title'];
     
     $site_logo=myTheme_options['site_logo'];
     
     

Examples:

![alt text](https://github.com/sajiddesigner/CMB2-Theme-Options-Panel/blob/master/cmb_to1.png "first Image")



![alt text](https://github.com/sajiddesigner/CMB2-Theme-Options-Panel/blob/master/cmb_to.png "2nd Image")


Please follow me at https://profiles.wordpress.org/sajiddesigner/

DEVELOPED WITH ![alt text](https://github.com/sajiddesigner/CMB2-Theme-Options-Panel/blob/master/heart.gif "Peace") LOVE IN ![alt text](https://github.com/sajiddesigner/CMB2-Theme-Options-Panel/blob/master/pakistan.png "Peace") PAKISTAN
