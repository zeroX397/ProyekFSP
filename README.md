# Full-Stack Programming Project

## Table of Contents
- [Full-Stack Programming Project](#full-stack-programming-project)
  - [Table of Contents](#table-of-contents)
  - [Introduction](#introduction)
  - [Part I: What is this project about?](#part-i-what-is-this-project-about)
  - [Part II: How about the Database?](#part-ii-how-about-the-database)
  - [Part III: How does the system work?](#part-iii-how-does-the-system-work)
  - [Part IV: Where is the link?](#part-iv-where-is-the-link)
  - [Part V: Seunik Mungkin](#part-v-seunik-mungkin)

## Introduction

This project serves as the Capstone for the Full-Stack Programming course at the University of Surabaya for the odd semester of the 2024/2025 academic year. It is designed to demonstrate a comprehensive application of modern web development techniques and technologies.

Utilising PHP and MySQL, the backbone of our web application supports robust data interactions, while front-end development with JavaScript and jQuery enhances the user experience by making the interface dynamic and responsive. The implementation of Responsive Web Design (RWD) ensures that our application is accessible and efficient across a variety of devices, providing an optimal viewing experience for users on both mobile and desktop platforms.

## Part I: What is this project about?

Our group is being tasked with creating a website for the E-Sport Management System, where users can create an account, apply and join teams, participate in events, and get achievements. In this system, user access is divided into two roles:

1. Admin

    This is the user's role with most access. Admin can manage every single category in the website. Admin cannot be registered unless they have access to the database and change the role's value. (We will discuss DB later)

2. Member
   
    This is the user's role with regular access. A user can register for an account and be automatically assigned as a member. A member can join a team by submitting an **Application Form**. After submitting, Admin can decide the application whether it will be accepted or rejected. A member can join more than 1 (one) team.

One team can be registered to ONE game only. But representatives of the team may contact the Admin if they want to play another game. Admin can a create new team with the same name, but a different game assigned to it. 

## Part II: How about the Database?

As we mentioned before, this project is using MySQL as the database. We also provide the Entity Relational Diagram (ERD) below:

![ERD of Database](/markdown-assets/ERD.png)
*Picture of ERD Structure of Database.*

As shown above, a member can join into many teams because many-to-many relation between `member` table and `team` table. But before a member apply to team, they need to make a "Join Proposal" to the desired team. We can see in `join_proposal` table, there are ID, member ID, team ID, description, and status of the proposal. The description field contains member's reason why they would like to join the team. It is limited by 100 characters only. The status field is ENUM, consist `waiting` (as default value when applying), `accepted` when admin accept the proposal, and `rejected` when admin denied the proposal.

The `team` has another many-to-many relation to `event`, which means an event can be attended by many teams, and a team can attend many event. This will be recoreded in `event_teams` and will be shown in teams' detail page, alongside with team's member.

Also if you want to get the SQL script of relational database, you can get it here: [SQL script](/markdown-assets/fsp-project-sql.sql)

## Part III: How does the system work?

For documentation read [here](/documentation/README.md)

We must clarify at first that this is **Full-Stack** Programming, NOT **Web-Framework** Programming. So there is no Laravel, CakePHP, CodeIgniter, or even Phalcon here. But we tried our best to implement the concept of Model, View, and Controller (MVC) into this project. We will explain two things from this project:

1. Navigation Bar (Navbar)

    The navigation bar remains the same in every page. But at the same time, it is also dynamic depends on *"user behavior"*. For the example, if we login to the website, profile section will adjust according to our user ID and username.

    ![Profile in Navigation Bar](/markdown-assets/profile-navbar.png)
    ![Profile in Navigation Bar](/markdown-assets/profile-navbar2.png)
    *The profile section will adjust based on logged in users.*

2. Admin Section

    *Of course we care about system's security. What do you think we are? MENKOMINF..<strong>[REDACTED]</strong>*

    Still on navbar, when user with Member role logged in to the site, they can not access the admin site, no matter what they are trying. But if admin logged in to the site, "Admin Site" and "Join Proposal" will appear at the most right side of the navbar.

    ![Admin Site](/markdown-assets/admin-navbar.png)
    *Admin Site and Join Proposal will appear if admin is logged in.*

    Admin Site is a dropdown contains every page of administration of each category. While Join Proposal is a dropdown to member's proposal management.

    ![Admin Site dropdown](/markdown-assets/adminsite-dd.png)
    *Admin Site dropdown contains every page of category management.*

    ![Join Proposal dropdown](/markdown-assets/joinproposal-dd.png)
    *Join Proposal dropdown contains proposal management page.*

    Also the administration page will check user's role. The system will check whether user is admin or not, (line 4 to 7, at all page under `/admin` directory) using these lines of code:

    ```php
    // Check if user is logged in and is an admin
    if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
        header('Location: /'); // Redirect non-admins to the homepage
        exit();
    }
    ```

    As shown above, if user is not an admin, they will be redirected to homepage. 

## Part IV: Where is the link?

You can access the website through this link:

*Link still unavailable yet.*

## Part V: Seunik Mungkin

This project would not have done without these great collabolators. Special thanks to our members:

1. 160422042 – [Oakley Levinson Gunawan](https://github.com/KaisarTomat)
2. 160422029 – [Stanley Alexander Gondowardojo](https://github.com/S10li909)
3. 160422073 – [Christopher Pengalilla](https://github.com/zeroX397)
