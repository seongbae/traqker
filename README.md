<!-- PROJECT SHIELDS -->
<!--
*** I'm using markdown "reference style" links for readability.
*** Reference links are enclosed in brackets [ ] instead of parentheses ( ).
*** See the bottom of this document for the declaration of the reference variables
*** for contributors-url, forks-url, etc. This is an optional, concise syntax you may use.
*** https://www.markdownguide.org/basic-syntax/#reference-style-links
-->
[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]



<!-- PROJECT LOGO -->
<p align="center">
  <img src="public/img/traqker-logo.png" alt="Logo" height="80">
  <p align="center">
    Traqker is an open source project and task management tool.
  </p>
</p>



<!-- TABLE OF CONTENTS -->
## Table of Contents

* [About the Project](#about-the-project)
  * [Built With](#built-with)
* [Getting Started](#getting-started)
  * [Prerequisites](#prerequisites)
  * [Installation](#installation)
* [Usage](#usage)
* [Roadmap](#roadmap)
* [Contributing](#contributing)
* [License](#license)
* [Contact](#contact)
* [Acknowledgements](#acknowledgements)



<!-- ABOUT THE PROJECT -->
## About The Project

![Product Name Screen Shot](public/img/traqker.png)

Traqker is a simple project and task management tool for small to medium-sized teams. 

Features:
* Track tasks, progress, and completion
* Projects
* Teams
* Discussion board
* Kanban board
* Gantt chart  
* Calendar
* Wiki
* Share files with teams
* E-mail & web notifications
* Archive tasks & projects

### Built With
* [Laravel](https://laravel.com) - backend
* [Bootstrap](https://getbootstrap.com) & [JQuery](https://jquery.com) - frontend
* [Canvas](https://github.com/seongbae/canvas) - admin panel

### Other libraries & tools used
* [fullCalendar](https://fullcalendar.io) - Calendar integration
* [jKanban](https://github.com/riktar/jkanban) - Kanban board integration

<!-- GETTING STARTED -->
## Getting Started

### Prerequisites

This is an example of how to list things you need to use the software and how to install them.
* PHP 7+
* MySQL
* Apache or nginx

### Installation

1. Clone the repo
```sh
git clone https://github.com/seongbae/traqker.git
```
2. Install Composer libraries
```sh
composer install
```
3. Install & compile NPM packages
```sh
npm install
npm run dev
```
4. Run the installation script
```sh
php artisan install:traqker
```
5. Browse to the site and login with 
```sh
Username: admin@admin.com
Password: password
```

<!-- USAGE EXAMPLES -->
## Documentation

Coming soon

<!-- ROADMAP -->
## Roadmap

* Project templates
* Translation
* Reporting
* LDAP integration
* OAuth2 client integration
* REST API
* Mobile applications

See the [open issues](https://github.com/seongbae/traqker/issues) for a list of proposed features (and known issues).

<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to be learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE` for more information.



<!-- CONTACT -->
## Contact

Seong Bae - [@baeseong](https://twitter.com/baeseong) - seong@lnidigital.com

Project Link: [https://github.com/seongbae/traqker](https://github.com/seongbae/traqker)






<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/seongbae/traqker.svg?style=flat-square
[contributors-url]: https://github.com/seongbae/traqker/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/seongbae/traqker.svg?style=flat-square
[forks-url]: https://github.com/seongbae/traqker/network/members
[stars-shield]: https://img.shields.io/github/stars/seongbae/traqker.svg?style=flat-square
[stars-url]: https://github.com/seongbae/traqker/stargazers
[issues-shield]: https://img.shields.io/github/issues/seongbae/traqker.svg?style=flat-square
[issues-url]: https://github.com/seongbae/traqker/issues
[license-shield]: https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square
[license-url]: https://opensource.org/licenses/MIT
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=flat-square&logo=linkedin&colorB=555
[linkedin-url]: https://linkedin.com/in/baeseong
[product-screenshot]: images/screenshot.png
