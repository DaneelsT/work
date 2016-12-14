# Work Webclient

System written in PHP [(Using Carbon Framework)](https://github.com/JoeriHermans/Carbon "Carbon Framework") to submit and check workshifts, track current monthly earnings, ...

## Version 3.0

###### Multilanguage support using GetText and basic API implementation
In version 3.0 support for multiple languages has been added using the gettext library. At the time of writing English (United States) and Dutch (Belgium) have been added. An API with basic functions has been implemented as well. Documentation on this at the bottom of readme. Possible expansions with this could be a mobile version of this project using a mobile framework (e.g. [Ionic](https://ionicframework.com/)).

## Version 2.0

###### More advanced webclient for usage by multiple people.
In this version of the work webclient, an entire usersystem has been implemented, thus usage by multiple people is possible.
The implemented usersystem and administrator features allow for an admin to send out an invite to a certain person, using their e-mail address which will then generate a unique registrationtoken.
The invited user will receive an email with a link to verify their account. From that point an account can be created and this person can login and manage their own workingshifts et cetera.

###### All actions are followed by a userid in the database.
In version 1.0 of the webclient all actions were not logged with a userid, because there wasn't a userinterface in the first place. When a shift gets added by a user, or a month gets booked, the
unique id of that user will get inserted together with the action in the database. This way actions per user can be easily accessed or displayed.

###### The administration panel
As said above, the administrator will have the ability to invite people to register an account on the work webclient. Besides this, the administrator will also be able to view shifts worked by
any other user in the system, as well as modify the information (such as name, email, password, hourly pay, ...) of an existing user or create a new user straight away without using the invite option.

## Version 1.0

###### Simple webclient for single person usage
In this version the work webclient was a rather simple webclient to be managed and used by one single indiviual only.
There were no sessions or other stored data which would make it possible to be used by multiple people at once.


PS: I suck at writing documentations and readme's.


Work API Documentation
----------------------
(just some basic documentation.)

#### Making API calls
API calls are validated based on the Authorization header. Always include this header when making API calls.
```php
# An example of a simple GET, retrieving the user's data of userid 1.
$.ajax({
    url: "/api/user/1",
    type: "GET",
    dataType: "json",
    headers: {
        "Authorization": "myToken",
    },
    success: function(data) {
        console.log(data);
    },
    error: function(error) {
    }
});
```

#### Retrieve account info from a user
**URL:** /api/user/{id}<br />
**Parameter:** {id} The id of the user to retrieve the info from<br />
**Response:** The user's details

#### Retrieve all registered users
**URL:** /api/users/<br />
**Response**: All users and their details

#### Retrieve all current shifts from a user
**URL:** /api/shifts/{id}<br />
**Parameter:** {id} The id of the user to retrieve their current shifts from<br />
**Response:** All current shifts from the user

#### Retrieve details of a certain shift
**URL:** /api/shift/{shift_id}<br />
**Parameter:** {shift_id} The id of the shift to retrieve details from<br />
**Response:** Details about the requested shift
