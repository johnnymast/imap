[![Build Status](https://travis-ci.org/johnnymast/redbox-package-skeleton.svg?branch=master)](https://travis-ci.org/johnnymast/redbox-package-skeleton)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/johnnymast/redbox-package-skeleton/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/johnnymast/redbox-package-skeleton/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/johnnymast/redbox-package-skeleton/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/johnnymast/redbox-package-skeleton/?branch=master)

# Redbox-Imap

This is the entire imap protocol in native php. No imap extention needed.
 
NOT READY!!

## installation 

TODO

## TODO

- [X] [Todo 2] CAPABILITY Command
- [X] NOOP Command
- [x] LOGOUT Command
- [ ] STARTTLS Command
- [ ] AUTHENTICATE Command
- [x] LOGIN Command    
- [X] SELECT Command
- [X] EXAMINE Command   
- [X] CREATE Command
- [X] DELETE Command
- [X] RENAME Command
- [X] SUBSCRIBE Command  
- [X] UNSUBSCRIBE Command 
- [X] LIST Command   
- [ ] LSUB Command   
- [X] STATUS Command 
- [ ] APPEND Command 
- [X] CHECK Command    
- [X] CLOSE Command    
- [ ] EXPUNGE Command   
- [ ] SEARCH Command 
- [ ] FETCH Command    
- [ ] STORE Command    
- [ ] COPY Command
- [ ] UID Command    
- [ ] X\<atom\> Command   


## Todo 2

- [ ] Test the AUTHENTICATE command against a server that supports it.
- [ ] LIST needs to be properly parsed.
- [ ] LSUB needs to be properly parsed.
- [ ] CAPABILITY needs to be properly parsed.
- [ ] Add option to options to keep the connection alive after one connection
- [ ] UNSUBSCRIBE is unconfirmed to work alto implemented.
- [ ] Add missing Argument exception to the commands (MissingArgumentException)


