# Gym Network-Oriented Information System

This project focuses on the development of a network-centric information system that supports the operations of a gym. The system includes two main subsystems: the administration system and the user system.

In the administrative subsystem, the administrator is able to manage registration requests, update user data, manage the structural elements of the gym, for example, the trainers , the programs and the calendars of the programs, and post announcements and promotions. 

In the user system, users have access to services such as browsing the gym's services, while registered users have access to product/service reservations, booking history, and viewing announcements. In addition, registration of a user in the system requires the user to complete the registration form and then have it approved by the administrator

## System Implementation

A number of technologies and techniques were used to implement the system.

Initially our database is implemented in MySQL. There are a total of 7 tables as shown below

![image](https://github.com/PanGian2/Gym_Network_Oriented-_Information_System/assets/122677298/a2879d11-fa99-4cdc-8e0f-87d996e92902)

In order to connect our database to our website we have implemented the necessary Rest APIs. The APIs are implemented in java with the help of the JAX-RS and Jersey library. In order to do this we have created a Dynamic Web Project named GymWService which we have converted it into a Maven Project.

