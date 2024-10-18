# Documentation of Full-Stack Project "E-Sport Management Website"

## Table of Contents
- [Documentation of Full-Stack Project "E-Sport Management Website"](#documentation-of-full-stack-project-e-sport-management-website)
  - [Table of Contents](#table-of-contents)
  - [Part I: Guest](#part-i-guest)
  - [Part II: Member](#part-ii-member)
    - [• Joining a team](#-joining-a-team)
    - [• Changing Profile Information](#-changing-profile-information)
  - [Part III: Admin](#part-iii-admin)
## Part I: Guest

"Guest" means users who do not have an account registered yet. Guest can explore the teams (including team's details), events, and about page only. It is indicating that Guest users have least access to the site. If users want to join a team, they must register an account through **Sign Up** page.

**Sign Up** can be done by clicking **Login**, then click <u>**Sign up here**</u>.

![How to Sign Up](to-signup.png)
*The "Sign Up here" hyperlink below Login Button.*

After pressing the hyperlink, users will be taken to a page where one must fill all required fields, such as: first name, last name (optional), username, password, and password confirmation. 

![Sign up page](signup-page.png)


If password confirmation does not match with password field, users can not proceed. But if does, user will redirected to login page. After registering an account, we move to the next step.

## Part II: Member

"Member" is a default role when an account is registered. With a member account, users can do such as:

### &bull; Joining a team
  
  Users can apply to be on its personnel. To become one of the team member, one must select a click **Teams** from top navigation bar. In **Teams** page, there are lots of teams that available to join. Choose one of them, and click **Details** if neccessary, and then click **Apply** button. User will be taken to the application form, where they need to fill out an application proposal.

  ![Teams page](teams-page.png)
  *You can click **Details** to see team's details. And then you may send application through **Apply** button*

  Please take a note that users **HAVE to fill** the application form. Write a short description about applicant at maximum 100 **CHARACTERS**. For example, users can describe about their favorite roles, main agents/heroes, etc. After done with it, users may send it by clicking **Apply** button.

  ![Fill the application form](application-form.png)
  ***Example only**. Fill the application form with short description. You may or may not include a contact (optional of course).*

  ![Success submit](success-submit-proposal.png)

  After you send the application, admins need to review it. Then decide to approve or reject the application. If the application is approved, you are one of the team's member. If rejected, you can apply again and may improved the application proposal. You can track application proposal in the profile page by clicking **Profile**, the green button (with your id - username) right side of **Logout** button. 

  ![Application proposal status](unavailable.jpg)
  *You can see you application status in **Profile** page.*

### &bull; Changing Profile Information

  User can change their personal information through **Edit** button in **Profile** page.

  ![Edit personal information](unavailable.jpg)
  *You can change your personal status here.*

## Part III: Admin

"Admin" is the most *powerful* role in this site.