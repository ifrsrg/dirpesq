=== User Registration Aide ===
Contributors: Brian Novotny
Donate link: http://creative-software-design-solutions.com/
Author URI: http://creative-software-design-solutions.com/
Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
Tags: user, members, membership, user management, registration, user registration, user profile, new user approve, verify email, security question, lost password, field, register, extra fields, profile, anti-spam, login message, login image, custom login css, admin, password change, password strength, password
Requires at least: 4.4
Tested up to: 4.7.3
Stable tag: 1.5.3.8

Adds custom user fields to better manage users & members & customize login-registration page css & messages. Lets you customize the entire user management experience to your own liking!

Note: WordPress 4.4 or higher is required due to new error handling procedures.

== Description ==

Customize the entire user management experience to your own liking!. Now you can have all user management features in one plugin! Includes New User Approve and Email Verification Features new in 1.5.3.0 and optional lost password security questions. Also allows you to add additional fields of any input type to registration form and profile for better user management and control. Customize the Default WordPress Registration Form & Login Page CSS & Messaging.  It also helps reduce unwanted spam registrations. Has anti-spam built in, customize default WordPress login/registration forms both in design and messaging, adds agreement policy and link to policy to registration form for members, can create custom redirects after login and registration, templates for password change and lost password and email verification, custom password strength options, and password update management. Can limit amount of time between password changes and the number of times before duplicate passwords allowed, set minimum password length and also require special characters, upper and lower case letters and numbers so your site is made more secure. New templates allow you to use custom password strength for both lost password and reset password options.   

WordPress User Registration Aide allows you to require more fields when a new user registers. This not only can help to stop spammers, but it can also increase your user management capabilities and services for your user base. All the new fields that you add also are added to existing users profiles, but the users will have to fill them out of course, but any new users will be required to fill out these fields if they are included in the registration process. The new fields can be any variety, select, text, radio button or checkbox, and all the standard html input types, and optional if you like. 

New in 1.5.3.0
New User Approve Feature
New User Email Verification Feature
Lost Password Reset Form Security Question for added Security.
All user features are added to the all users table for easy access to approve, deny, resend emails, activate, and delete denied users.

Another important option is that you can also add new fields to the users profile page but not require them for registration, so you can increase you user management capabilities. This is an exciting new feature for Web-masters that wish to increase contact options, communications, and information obtained from your user base.

Plugin Features:

    Easy to use
    Quickly and easily add new fields to user registration
    Add your own custom fields or existing fields like First Name, Last Name & Nickname to the registration form
    Reduce bots and spammers with additional fields on the registration form
    Get better control over your user base
    New fields are added to existing WordPress User Profiles!
    Increase your knowledge of, and interaction with your customers & users!
	Add Custom Logo & Messages to Registration & Login Pages!
	Add Custom Background Image or Background Color to Login & Registration Pages!
	Anti-Spam Math Problem
	Password Strength Meter
	Custom Password Strength Options to Create Your Own Password Strength Definition
	Choose custom display name fields for users by roles or for all users
	Force New Users to change password after getting password from email
	Force Existing Users to change password after specified amount of time
	Not allow same password to be used for specified number of times
	Optional fields on registration form -- Use or don't use * for required fields
	Different input types for fields like checkbox, select, radio buttons, numbers
	New User Approve/Deny/Delete Feature
	New User Email Verification
	Lost Password Reset Security Questions
	

Read more: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/#ixzz22CCABfOx

Instructions: http://creative-software-design-solutions.com/wp-content/uploads/2017/05/USER_REGISTRATION_AIDE_INSTRUCTIONS.pdf

Instruction Video for using new filters to fix URA custom page template styling issues: https://youtu.be/nar71ttdjVg

== Installation ==

Download the file and put it in your plugins directory.

Activate it.

Method 2 (WordPress Add New Plugin):

    Go to ‘add new’ menu under ‘plugins’ tab in your word-press admin.
    Search ‘User Registration Aide’ plugin using search option.
    Find the plugin and click ‘Install Now’ link.
    Finally click activate plug-in link to activate the plug-in.

[See Installation Instruction and Configuration information and Demo](Read more: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/#ixzz22CDaarHi)	


To configure what fields are required for new users to register an account, login to the WordPress Dashboard. Then go to the Dashboard Home Screen -> Administration → User Registration Aide → User Registration Aide - Edit New Fields

Add Additional Fields to User Registration Form Option:

This section includes a drop down box with all the existing fields in the WordPress profile, and all the additional new fields that you add, if any. None of them will show up on the registration form until you click the field you want to add to the registration form with your mouse. You can select multiple fields here by holding down the control button (CTRL) while clicking on the fields of your choice to add to the registration form.

Read more: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/#ixzz22CEi4lRR

== Frequently Asked Questions ==

= Is there a shortcode?  =

No, there currently is no shortcode as it uses the current registration/profile actions and filters for WordPress so it shows up without the need for a shortcode.

= Where are the new fields? =

First, you need to make sure you are allowing new users to register for your site which is found on the general settings tab on the admin dashboard under membership, make sure you have the Anyone can register checkbox checked.

Then, if you don't know where it is the registration form is on the login form, as there should be a register link at the bottom of the login form page if you are allowing anyone to sign up. Click on the register link to see the registration form and your new extra fields.

= Any Known Installation Issues? =

Yes, with W3 Total Cache activated there will be errors thrown when creating the new template pages. It will say post_permalink is deprecated if you have error reporting on, and apparently will prevent activation on some sites from my understanding. I have no idea when they will fix that and there is nothing we can do at this point in time, others have addressed this issue on their support page but they are still using deprecated code and generating errors. What you can do to work around this is before activating User Registration Aide, deactivate W3 Total Cache and activate USer Registration Aide and then reactivate W3 Total Cache.
 
For the Email Verification and other functions to work properly you must have the Permalink Structure set to Post Name, otherwise you will get Error 404 Page Not Found Errors!

== Screenshots ==

1. Plugin Page
2. WordPress Administration Dashboard
3. Admin Page 1 User Registration Aide - Add New Fields
4. Admin Page 2 Edit New Fields - Registration Fields
5. Admin Page 3 Registration Form Options - Password Strength Requirements
6. Admin Page 4 Registration Form Messages & CSS Options - Messages
7. Admin Page 4 Registration Form Messages & CSS Options - CSS Options
8. Admin Page 5 Custom Options - Password Change Options
9. Admin Page 2 Edit New Fields - Field Orders & Titles
10. Admin Page 2 Edit New Fields - Delete Options & Edit Option Titles
11. Login Page 100% zoom Not Scrolled
12. Login Page Full Page 75% zoom

== Changelog ==

1.5.3.8

a) Fixed bug with css

b) Fixed other bugs

c) Added text size option to login & registration forms

d) added filters to compensate for plugin page templates css so they can use current theme styling

1.5.3.7

a) Fixed bug with wp_authenticate function

b) Fixed potential bug with custom templates

1.5.3.6

a) Fixed bug with wp_authenticate function

1.5.3.5

a) Fixed bug with custom template stylesheet naming errors

b) Fixed bug with Dashboard Widget Image Error

c) Fixed bug with new user approve/verify email login before approved or verified

d) Partial Danish Translation added thanks to Isommerdal

1.5.3.4

a) Fixed bug with lost password and new user approve

1.5.3.3

a) Fixed bug with Registration Form CSS Settings Page not updating properly

1.5.3.2

a) Fixed bug with admin creating new user email notification

b) Fixed potential bug with creating new custom template pages 

c) Fixed several small potential bugs

1.5.3.1

a) Email verification bug fixed, email confirmation page not showing up on some themes

b) fixed a few minor bugs

1.5.3.0

a) added new user approve feature so users do not have access to the site until they are approved by an administrator 

b) added new user email verification so new user cannot login and is not active until their email is verified

c) added lost password form security questions giving better security to sites

d) added user tables to main user table for new user approve, deny, verify email and password set

e) added page templates for lost password, update password, and email verification 

f) fixed some bugs and cleaned up some code and languages

g) menus updated

h) updated user interface

i) fixed password nag so it is really removed if user enters own password at registration

1.5.2.6

a) added plugin check for wp-symposium-pro on profile page nonce check

1.5.2.5

a) profile update for profile update page for Asgaros Forum

1.5.2.4

a) fixed custom css file bug

1.5.2.3

a) Fixed bug with debug log

1.5.2.2

a) Fixed bug

1.5.2.1

a) Fixed bug 

1.5.2.0

a) Added different field data types

b) Added option fields to select/checkbox/radio/multiselect data types

c) Updated user Interface

d) Fixed some potential bugs

e) Added Hindi, Portuguese, Russian and French translations

f) Added debug log to help with troubleshooting 

g) Updated screenshots for new user interface

h) Added Help Screens

1.5.1.7

a) fixed bugs with email notification

1.5.1.6

a) updated login page css styling

1.5.1.5

a) fixed bugs with wordpress messages

1.5.1.4

a) fixed bugs with New User Approve Plugin updates 


1.5.1.3

a) fixed bugs with New User Approve Plugin updates 

1.5.1.2

a) undid changes for New User Approve not work right on other installs needs more testing

1.5.1.1

a) fixed bugs with integration for New User Approve plugin

b) updated New User Approve user registration to include new WordPress new user registration process ( no password by email )

1.5.1.0

a) fixed languages bugs

b) added Spanish translations

c) added German translations

d) minor bug fixes

1.5.0.9

a) fixed bug

1.5.0.8

a) fixed bug on registration form

1.5.0.7

a) modified anti-bot-spammer protection for better success to keep bots away

b) updated bug with email notifications

c) added option to add optional fields for registration form

d) added option to use * ( Asterisk ) for required fields on Registration Form or take it off

e) updated languages files and added Spanish translation

1.5.0.6

a) updated email notifications

1.5.0.5

a) bug

1.5.0.4

a) Fixed Password Field Issues

1.5.0.3

a) bug fix 

1.5.0.2

a) Added nonce to Password Change Page

b) Removed url display at top if page (From testing)

1.5.0.1

a) Changed a few labels on password change options section

1.5.0.0

a) Added Password Change Shortcode to have front end custom password change page.

b) Added password change to force new users and existing users to change password after they get a new password in email or they haven't changed password in specified amount of time. 

c) Can't use old passwords for a specified amount of time.

d) Redirect to Change Password to SSL if available

e) Remove password fields form profile page for non-admins

f) Disable Lost Password Option from login page for non-admins

1.4.0.2

a) svn bug

1.4.0.1

a) math problem bug

1.4.0.0

a) added option to change display names for users by select role or all roles and choice of fields

b) added custom css options for those that find the interface not to their liking

c) changed registration form Bio field so that it will now be a text area instead of a text box input

d) deleted some old code that was not needed any more and cluttering up files

e) make some new adjustments to account for Theme My Login Plugin

1.3.7.4

a) Fixed math problem bug where it wouldn't update math problem settings correctly

1.3.7.3

a) Fixed Potential Dashboard Widget Issue

1.3.7.2

a) Updated Registration/Login form custom logo code

1.3.7

a) Minor bug fixes and cleanup

1.3.6.1

a) Fixed potential bug with anti-spam math problem allowing user to select no math operator (+, -, /, *) while using the anti-spam math problem for registration.

1.3.6

a) Added options to modify WordPress dashboard widget fields.

b) Added options to modify math problems so you can choose which operators to use and made the random numbers smaller so it won't be as difficult.

1.3.5

a) Added options to modify password strength requirements, either use default or make custom or use none.

1.3.4

a) Updated bug for email to new users who enter own password to not email password.

1.3.3

a) Updated email to new users who enter own password to not email password.

1.3.2

a) Redid Password Meter

b) renamed .mo file

1.3.1

a) Added .pot and .mo files to languages

1.3.0

a) updated interfaces

b) added anti-spammer math question to registration form

c) added dashboard widget

d) added option to add new fields to administrators add new user page but you have to hack the core code at this time to do that.

e) fixed a few bugs

f) updated comments and code

1.2.8

a) fixed another potential bug with database options update & with agreement checkbox

1.2.7

a) fixed another potential bug with database options update & with agreement checkbox

1.2.6

a) fixed potential bug with database options update

1.2.5

a) Fixed bug with login/registration form for emailing password & password reset

1.2.4

Thirteenth Revision minor

a) Fixed delete new field bug

b) Combined code for headers for quicker loads

c) Got rid of 'unexpected characters' when plugin is activated bug

d) Made admin pages easier to view

e) Added login-registration page link shadows options so you can either remove them or adjust them to your liking as the old version looked a little funky to me, so if it does to you to you can now either change the size and color or just don't show the shadow at all.

1.2.3

Twelfth Revision minor

a) Fixed registration form bug

1.2.2 

Eleventh Revision Minor

a) Found missing errors script 
1.2.1 

Tenth Revision Minor

a) Somehow errors for other plugins were showing up even though I had those error reportings commented out so I deleted them

1.2.0

Ninth Revision - Major Revision

a) Updated options and items and consolidated database usage

b) Added Website to existing fields

c) Added Password and Password Confirm to existing fields

d) Added FUNCTIONAL Password Strength Meter!

e) Users can login right after registering with new password!

f) Option to change message to user when registering if they can enter their own password instead of default a password will be mailed to you

g) Added option to change login/registration page logo and background image or color to your own logo, image, or color!

h) Change the label/nav colors of login/registration forms so they better work with your custom background images/colors

i) Added option to add message and check box and link to agreement/policy form if user must submit and agree to certain conditions when registering
	for website. (User Requested)

j) Other code upgrades and modifications and button enhancements

1.1.4 

Eighth Revision – Minor Revision

a) Fixed minor bug with non-admin users editing profile

1.1.3

Seventh Revision - Minor Revision

a) Fixed minor bug with error reporting

1.1.2

Sixth Revision - Minor Revision

a) Fixed tabbing index in registration page

b) Minor updates and fixed potential problem with profile updates

1.1.1

Fifth Revision
Minor Revision

a) Fixed some minor bugs that appeared after release of 1.1.0

1.1.0

Major Revision Final 8/14/2012 5PM EST

a) Added the option to delete additional new fields that you added and don't want or need

b) Added the option to change the order of the new fields at any time which will be reflected on both the registration page and the profile page

c) Changed the looks somewhat by adding tables to make it more orderly

d) Fix some minor bugs and oversights on my part

e) Made the code more easy to read and added more comments

f) Added more security features like nonce and updated filtering new content being added and also checking user levels and making sure they have access to features

g) Changed menu location to it's own menu instead of in the users menu, giving me the option to add more pages and located it right below the users menu tab

h) Upgraded the delete plugin function so it will delete all the options, so if you aren't sure whether you'll use it again or not, you may want to avoid deleting it until you are sure otherwise all user inputs into new fields will be lost as well as all your options, settings and new fields

i) Added internationalization, so now users from foreign countries should have translated versions available, at least if I did it right, I hope so, if not give us a holler!

1.0.2

Third Version 8/1/2012 9 PM EST

Fixed bug of open line between PHP stop and start points which created header errors

1.0.1		
	
Second version. Final 8/1/2012

Added a few updates to looks, more importantly fixed profile update bug so it works with new fields now and updates them properly. Also added filters to new user registrations for new fields.

1.0.0		
	
First version. Final 7/31/2012

New improved version for Force Users Field Registrations with some added features. First release stable, has add new field options, add existing and new fields to user registration, adds all new fields to current users profile pages, and gives you the option to require or not require when new members sign-up.




























