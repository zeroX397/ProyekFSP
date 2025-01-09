Full-Stack Programming Project
=====

# Table of Contents
- [Full-Stack Programming Project](#full-stack-programming-project)
- [Table of Contents](#table-of-contents)
- [Introduction](#introduction)
- [Part I: What is this project about?](#part-i-what-is-this-project-about)
- [Part II: How about the Database?](#part-ii-how-about-the-database)
- [Part III: How does the system work?](#part-iii-how-does-the-system-work)
- [Part IV: Where is the link?](#part-iv-where-is-the-link)
- [Part V: Seunik Mungkin](#part-v-seunik-mungkin)

# Introduction

This project serves as the Capstone for the Full-Stack Programming course at the University of Surabaya for the odd semester of the 2024/2025 academic year. It is designed to demonstrate a comprehensive application of modern web development techniques and technologies.

Utilizing PHP and MySQL as the core of our web application, we enable robust data interactions, while front-end development with JavaScript and jQuery enhances the user experience by making the interface dynamic and responsive. The implementation of Responsive Web Design (RWD) ensures that our application is accessible and efficient across a variety of devices, providing an optimal viewing experience for users on both mobile and desktop platforms.

# Part I: What is this project about?

Our group is tasked with creating a website for the E-Sport Management System, where users can create accounts, apply for and join teams, participate in events, and earn achievements. In this system, user access is divided into two roles:

1. Admin

    This is the user's role with the most access. Admins can manage every single category on the website. Admin accounts cannot be registered normally; they require access to the database to change the role's value. (We will discuss the database later.)

2. Member
   
    This is the user's role with regular access. A user can register for an account and is automatically assigned the role of a member. Members can join a team by submitting an **Application Form**. Once submitted, the Admin can decide whether to accept or reject the application. A member can join more than one team.

Each team can only be registered for one game. However, team representatives may contact the Admin if they want to participate in another game. Admins can create a new team with the same name but assign it to a different game.

# Part II: How about the Database?

As mentioned earlier, this project uses MySQL as the database. Below is the Entity Relationship Diagram (ERD):

![ERD of Database](/markdown-assets/ERD.png)
*Picture of ERD Structure of the Database.*

As shown above, a member can join multiple teams due to the many-to-many relationship between the `member` table and the `team` table. However, before a member applies to join a team, they need to submit a "Join Proposal" for the desired team. In the `join_proposal` table, there are fields for ID, member ID, team ID, description, and status of the proposal. The description field contains the member's reason for joining the team, limited to 100 characters. The status field is an ENUM type, with values `waiting` (default when applying), `accepted` (when the Admin approves), and `rejected` (when the Admin denies the proposal).

The `team` table also has a many-to-many relationship with the `event` table, meaning an event can include multiple teams, and a team can participate in multiple events. This relationship is recorded in the `event_teams` table and displayed on the team's detail page, alongside the team's members.

If you want to download the SQL script for the relational database, you can get it here: [SQL script](/markdown-assets/fsp-project-sql.sql).

# Part III: How does the system work?

For detailed documentation, read [here](/documentation/README.md).

First, we must clarify that this is a **Full-Stack Programming** project, NOT **Web-Framework Programming**. Thus, frameworks like Laravel, CakePHP, CodeIgniter, or Phalcon are not used. However, we have tried to implement the Model, View, and Controller (MVC) concept in this project. Below, we explain two key features:

1. Navigation Bar (Navbar)

    The navigation bar remains consistent across all pages but dynamically adjusts based on *user behavior*. For example, when logged into the website, the profile section updates to reflect the user's ID and username.

    ![Profile in Navigation Bar](/markdown-assets/profile-navbar.png)
    ![Profile in Navigation Bar](/markdown-assets/profile-navbar2.png)
    *The profile section updates based on logged-in users.*

2. Admin Section

    *Of course, we care about system security. What do you think we are? MENKOMINF..<strong>[REDACTED]</strong>*  

    On the navbar, when a user with the Member role logs in, they cannot access the admin site regardless of their attempts. However, when an Admin logs in, "Admin Site" and "Join Proposal" options appear on the far right side of the navbar.

    ![Admin Site](/markdown-assets/admin-navbar.png)
    *Admin Site and Join Proposal options appear for Admin users only.*

    The Admin Site dropdown contains links to all category management pages, while the Join Proposal dropdown leads to the member proposal management page.

    ![Admin Site dropdown](/markdown-assets/adminsite-dd.png)
    *Admin Site dropdown contains links for managing categories.*

    ![Join Proposal dropdown](/markdown-assets/joinproposal-dd.png)
    *Join Proposal dropdown contains proposal management pages.*

    Additionally, the system verifies the user's role for all administration pages. The following code checks if a user is an Admin (present on all pages under the `/admin` directory):

    ```php
    // Check if the user is logged in and is an admin
    if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
        header('Location: /'); // Redirect non-admins to the homepage
        exit();
    }
    ```

    If the user is not an Admin, they are redirected to the homepage.

# Part IV: Where is the link?

You can access the website through this link:

*Link is currently unavailable.*

# Part V: Seunik Mungkin

This project would not have been possible without these great collaborators. Special thanks to our members:

1. 160422040 – [Oakley Levinson Gunawan](https://github.com/KaisarTomat)
2. 160422029 – [Stanley Alexander Gondowardojo](https://github.com/S10li909)
3. 160422073 – [Christopher Pengalilla](https://github.com/zeroX397)
