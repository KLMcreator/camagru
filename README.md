# Camagru

A social network where you can take/upload pictures and add funny filters to it.

# Developed with

- HTML
- CSS
- PHP
- [MySQL](https://www.mysql.com/)
- [Bulma](https://bulma.io/)

# Informations

Keep in mind this project was made in a "rush" so the code might not be as clean as possible, comments were also removed to avoid "cheating / stealing"

# Constraint imposed by 42

- Nothing else than native HTML, CSS and PHP
- CSS Frameworks allowed, nothing else
- Overlapping (picture and filter) done on server side
- Crypted passwords, no XSS or major security issues
- Works on Firefox >= 41 and Chrome >= 46
- No warnings or console logs except HTTPS related

# Dependencies

This was made using [BitnamiMAMP](https://bitnami.com/stack/mamp) but any developement stack with PHP, MySQL and Apache will do it.

# Installation

To populate your database just go to the config folder and run

```bash
php -f setup.php
```

# Usage

Start MAMP or the stack you use to run your apache server, port is hardcoded so make sure you use the 8080 port

# Features

- Take picture or upload picture and add filters to them<br />
- Live and final render<br />
- AJAXified queries<br />
- Comments and likes from gallery and snapshot page<br />
- Infinite page (no loading)
- Share link to social networks (might not work in the future as it changes often)<br />
- Delete your own photo montages, comments and account

# Contributing

This project won't be updated but if you want to pull something, feel free to do so.

# Authors

- Clement [KLMcreator](https://github.com/KLMcreator) VANNICATTE
