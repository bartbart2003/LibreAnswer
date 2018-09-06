# LibreAnswer - Contributing
## What can you do?
### I am a developer/programmer
You can add new features or fix bugs. If you made an improvement, feel free to open a pull request on GitHub.
Remember to read the **LibreAnswer contributing guidelines** section in this file.
### I'm not a developer/programmer, but I still want to help
You can:
 - create question packs - you can use LibreAnswer Creator http://creator.bartbart.pl
 - review question packs submitted by users - comment on pull requests at https://github.com/bartbart2003/libreanswer-packs/pulls
 - help translating LibreAnswer, for example by adding support for your native language or improving existing translations. I recommend using the POEdit tool
 - search for bugs and report them using the "Issues" tab on GitHub
 - help documenting this project
 - comment on issues and pull requests
 
## LibreAnswer contributing guidelines
- Please follow **camelCase** naming convention
- prepared SQL statements should be used where possible
- code should not be vulnerable to attacks like SQL injection
- you should use htmlentities() where needed
- **EVERYTHING** (variable names, comments, ...) must be in **English** (excluding translations, they will be happily accepted)
- web pages visible to the user should be responsive (non-responsive short error messages are OK)
- please give pull requests descriptive names and describe your changes in description
- you must make sure that your code works before starting a pull request
