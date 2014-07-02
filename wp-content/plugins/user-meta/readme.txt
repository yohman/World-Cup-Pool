=== User Meta ===
Contributors: khaledsaikat
Donate link: http://user-meta.com/donation
Tags: user, profile, registration, login, frontend, users, usermeta, import, csv, upload, AJAX, admin, plugin, page, image, images, photo, picture, file, email, shortcode, captcha, avatar, redirect, register, password, custom, csv, import, user import, widget
Requires at least: 3.3.0
Tested up to: 3.8.1
Stable tag: 1.1.5
Copyright: Khaled Hossain, user-meta.com
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress user management plugin. Custom user profile,registration with extra fields. Custom Login, Import/export users from/to csv and many more.

== Description ==

= WordPress user management plugin =

Support custom user profile, both back-end and front-end. Custom registration with extra fields. Login widget/shortcode, user login by username or email. Import user from csv with meta data.
Themes the WordPress profile, register and login pages according to your theme. Add extra fields(meta data) to user profile or registration page, User Meta plugin support variety of fields to create profile or registration form. More features: admin approval, user activation/deactivation, email verification, role based redirection, modify default email sender, custom email template etc.

= Custom user profile and registration page =

User Meta Pro allow to fully customize user profile or registration page by providing form editor tools. any page or post can be use as profile/registration page by using shortcode. support ajax user avatar, ajax file upload, and ajax input validation, captcha validation, pagination for break long page to paginated page. Let user login with or without username or email.

= Login widget/shortcode, login with username or email =

Let user to login by email instead of username. Or both email or username. Customize login form by adding own html. Customize logged in user profile.

= Front-end lost password and reset password tools =

wp-login.php is no longer needed for lost password and reset password. Also restrict access to wp-login.php and redirect them to front-end login page.

= Import user from csv =

Import user from csv with extra meta data. Assign role to newly imported user. Update current user data by csv file.

= Redirection =

Role based redirection after user login, logout and registration.

= Admin approval, user activation or deactivation =

Allow admin to approve user upon registration and activate or deactivate any user, any time.

= E-mail verification =

Verify user email in order to activate a new user.

= Customize Email Template =

Customize user registration, activation, deactivation, lostpassword etc emails with including extra fields data.

= Modify default email sender information =

Let your user get email from your prefered name and email instead of wordpress@userdomain.com

= Supported field for form builder =

* User Avatar
* TextBox
* Paragraph
* Rich Text
* Hidden Field
* DropDown
* CheckBox
* Select One (radio)
* Date /Time
* Password
* Email
* File Upload
* Image Url
* Phone Number
* Number
* Website
* Country
* Custom Field
* Page Heading
* Section Heading
* HTML
* Captcha

You can create unlimited number of fields. All newly created field's data will save to WordPress default usermeta table. so you can retrieve all user data by calling wordpress default functions(e.g. get_userdata(), get_user_meta() ). User Meta plugin separates fields and forms. So, a single field can be used among several forms.

= Documentation =

**3 steps to get started**

1. Create Field from User Meta >> Fields Editor.
1. Go to User Meta >> Forms Editor, Give a name to your form. Drag and drop fields from right to left and save the form.
1. Write shortcode to your page or post. e.g.: Shortcode: [user-meta type='profile' form='your_form_name']

[View Documentation](http://user-meta.com/documentation/ "User Meta Pro Documentation")

**NB:** User Registration, login and some extra fields are only supported in pro version.
Get [User Meta Pro](http://user-meta.com/ "User Meta Pro").


== Installation ==

1. Upload and extract `user-meta.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Why error message, "User registration is currently not allowed." is showing in registration page? =

WordPress doesn't allow to register new user by default settings. To allow user to register, go to Settings >> General page in admin section. Checked the checkbox which is saying "Anyone can register" and Save Changes.


== Screenshots ==

1. Fields Editor
2. Forms Field selector
3. Frontend Profile
4. Frontend Login

== Changelog ==

= 1.1.5 =
* Add user_id parameter to user_meta_pre_user_update filter hook.
* Remove not used uploaded files via schedule events.
* Add Russian translation. (Thanks to Vitaliy Cherednichenko for his translation)
* Add placeholder support to Fields Editor.
* Change file upload directory to /uploads/files/
* Change logout url. Logout url is not using resetpass anymore.
* Add html5 placeholder to field by user-meta hook.
* Bug fix: Required validation for country field.
* Add settings for customize UMP generated text for front-end.
* Pro: Allow to send email notification for all users who have administrative role.
* Pro: User password will not force to include in registration email notification.
* Pro: Add option for use default lostpassword url.
* Pro: Remember last user import settings.
* Pro: Use separate page for reset password and email verification.
* Pro: Login redirect will not show any message while redirecting.

= 1.1.4.1 =
* Add Captcha for login form.
* Fix import UMP and pagination bugs.

= 1.1.4 =
* Use TinyMCE as rich text editor.
* meta_key auto generate from field title.
* Enable translation for dynamic text.
* Disable free to pro one click update as WordPress plugin guidline.
* add allow_custom in datetime field to allow more customization by js.
* Added alternate method when allow_url_fopen=0 for showing uploaded image.
* Field validation in both server and client side.
* Pro: Introduce new field type "Custom Field" to add custom regex and error message.

= 1.1.3 =
* Assign form to login widget.
* Allow role based profile as widget.
* Replace type=both into type=profile-registration
* Replace type=none into type=public
* type=public allow user_id as $_GET for showing public user profile.
* Add type=login to form widget.
* Change date format and filter hook.
* Default role selection.
* Shortcode generator popup.
* Clickable checkbox and radio.
* Added filter: user_meta_pre_configuration_update for fields_editor, forms_editor and settings.
* Action: user_meta_load_admin_pages
* Filter: user_meta_execution_page_config
* Filter: user_meta_default_login_form
* Aded filter support to lost password form and deafult login form.
* Theme for reCaptcha.
* Check user access by â€œadd_usersâ€ capability.
* Clickable users listing for Active | Inactive | Pending | Pending Approval
* Change email verification and reset password process.
* WordPress-3.5 compatibility.
* UMP Export-Import fields, forms, settings.
* Role based profile showing.
* Allow role selection on registration/profile (admin can choose which roles user can select).
* Field title position: Top, Left, Right, Inline, Hidden.
* Added â€œAuto login after user registrationâ€ feature.
* Fixes: Password changing from frontend.
* Image crop for avatar or file upload.
* Single pot file.
* Enable SSL admin.
* Assign custom form with login widget/login form that allow to use custom field, class name, changing button text/class.
* Integrate plugin-framework.pot with user-meta.pot (single pot file instead of two).
* Provide more action/filter hook in every steps.
* Allow to use placeholder under html field.
* MU: New blog registration.
* MU: Add user to blog.
* MU: added option for prevent login for non-member for current blog.
* Registration/Profile widget.
* Registration/Profile Template Tag.
* Extended users export.
* Allow to change buttonâ€™s text and css class of form.
* Custom email notification for profile update(both user and admin).

= 1.1.2 =
* One click upgrade to Pro version.
* Add default email sender support.
* Pro: One click version update.
* Pro: Login widget. Showing role based user data with login widget or shortcode.
* Pro: Extra fields in backend profile.
* Pro: Role based customizable email notification with extra fields.
* Pro: Import users from csv file including user's meta data.
* Pro: Front-end lost password and reset password tools.
* Pro: User email verification on registration.
* Pro: User activation and deactivation.
* pro: Role based user redirection on registration, login and logout.

= 1.1.1 =
* Added Support while fail AJAX call

= 1.1.0 =
* Include first version of User Meta Pro
* Pro: added more fields type
* Pro: Frontend Registration
* Pro: Frontend Login with username or email

= 1.0.5 =
* Changing complete structure
* Make Seperation of fields and form, so one field can be use in many form
* Add verious type of fields
* Added dragable fields to form
* Improve frontend profile

= 1.0.3 =
* Extend Import Functionality
* Draggable Meta Field
* Add Donation Button

= 1.0.2 =
* Optimize code using php class.
* add [user-meta-profile] shortcode support.

= 1.0.1 =
* Some Bug Free.

= 1.0 =
* First version.

== Upgrade Notice ==

= 1.1.1 =
* Added Support while fail AJAX call.

= 1.1.0 =
* Introduce with User Meta Pro.

= 1.0.5 =
* Added new fields with new look and feel also functionality.

= 1.0 =
* First version.