# **WARNING: THIS PROJECT IS IN BETA! EVERYTING CAN BE ADDED, CHANGED OR REMOVED WITHOUT NOTICE!**
# LibreAnswer
## Official server
You can play LibreAnswer on the official server at http://ans.bartbart.pl.
## About
LibreAnswer is a game and a test making tool inspired by the "Who wants to be a millionaire?" TV show.
You can create question packs and then let people play them.
## Features
### General:
- question packs support (3 different types, described later)
- two types of questions - ABCD and true/false
- admin panel
- test making
- responsive design
### In-game:
- support for lifelines (hint and 50/50)
- support for aborting game
## Question packs types
There are 3 types of question packs. They are described below.
### Standard
Standard mode, game ends after a wrong answer. User score isn't stored on the server.
### Quiz
Game doesn't end after a wrong answer. Score isn't stored on server, but is displayed to the player after the game ends.
### Test
User can freely navigate between the questions.
The answers are not checked during gameplay, but they are stored on the server and they are visible in the admin panel (username, answers, score, percentage).
**Warning: please do not use "tf" question type in "test" pack type. It may cause unexpected behavior. Also, extra info for questions isn't supported in this type of pack.**
## Installation
Installation instructions are in the INSTALL file.
## Contact
If you want to report a bug, ask a question or request a new feature, feel free to use the "Issues" tab on GitHub (https://github.com/bartbart2003/libreanswer/issues).
If you are reporting a bug, please try to follow the template in ISSUE_TEMPLATE.md (contents of this file should load automatically).
Issues reported on GitHub **must** be in **English**.
## Contributing
Contributing information and guidelines are in the CONTRIBUTING.md file.
If you want to get involved, please read it.
## Question packs repo
Our official question packs repository is available at https://github.com/bartbart2003/libreanswer-packs.
