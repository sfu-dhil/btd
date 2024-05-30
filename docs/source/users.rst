.. _users:

User Management
===============

Keeping track of users is complicated in any application. To that end,
there are several important concepts:

Authentication
  How a user establishes their identity. This is typically done via
  logging in with a username and password, but other methods are also
  used in different applications.

Authorization
  Different users can do different things. A user that has
  authorization to do something is given a permission.

Role
  Users with the same set of permissions are grouped into roles. All
  the users that can add and edit content might be have the role
  *content admin* for example. Users can have multiple roles.

  Roles are described in :ref:`roles`.

Authentication
--------------

If a user visits the site and hasn't logged in, there isn't much they
can do. The user can look at things, but shouldn't be able to make any changes.

There's a login link in the top right of every page. The user can use
that login link to sign in to the site with an email address and
password. This authenticates the user.

Once the user has successfully logged in, that link becomes a user
menu. It includes menu items to logout, change password, and view and
edit the user's profile.

Authorization
-------------

Users in the role *user admin* can add and edit users, user
permissions, and user passwords. This is a very powerful role, and
should be given out very carefully.

Creating New User Accounts
--------------------------

For users with the correct authorization, creating a new user account
should be easy. There's a *Users* item in the user menu at the top
right. Follow the link to get a list of users. There's a New button
for creating new users. Fill out the form, and be sure to check the
Account Enabled checkbox. Select the appropriate roles for the user.

Once a user account has been created, the administrator needs to set a
password for the user. Go to the user list and click on a user's full
name. There is a button there to set the user's password.

