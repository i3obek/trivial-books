*## SHORTCUT START ##*

#
***!! ## !! TAKE CARE OF `.env` !! ## !!***

Beside project root `.env`, there is also docker-`.env` in `.tools/docker` directory
#

`cd .tools`

`./install.sh` (***first run only***, every time it's run again with `./docker.sh`)

`./docker-stop.sh`


*## OPTIONALLY ##*

`chmod +x install.sh` / `chmod +x docker.sh`

*## RUN ##*

- start `./docker.sh`

- stop `./docker-stop.sh`


**## ↓↓↓ FOR DETAILED `RUN ON DOCKER` INSTRUCTION, CHECK [THIS DESCRIPTION](.tools/README.md) ↓↓↓ ##**

****


**APP DESCRIPTION**

This is very trivial and basic CRUD-type app, where You can make list of books with authors.

There are only 2 Entities, `Book` and `Author`, and contains properties as follow:

`Author`
- First Name
- Last Name

`Book`
- Name
- Description
- `Author`

Before You will be able to add new `Book`, You need to add `Author` first.

Each `Author` can be associated with many `books`, but unfortunately `Book` can have only one `Author`

At this moment app is very non-optimal, ex.
- pagination isn't implemented as standalone functionality and can be problematic with large set of data
- no caching

Bugs
- doubled `_id` suffix for author-book relation, missed when naming and caused because of it.