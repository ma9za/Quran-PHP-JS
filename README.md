# Quran-PHP-JS
Website for the Holy Quran

## Introduction
This website provides a platform for users to listen to Quran recitations by various renowned reciters. It features a simple, user-friendly interface for accessing and playing the audio of Quran suras (chapters).

## Features
- **Reciters List**: Browse a list of Quran reciters.
- **Sura Details**: View and listen to the available suras for each reciter.
- **Visitor Count**: Tracks the number of visitors to the site.
- **Responsive Design**: The site is built with a responsive layout for compatibility with various devices.
- **Search Functionality**: Users can search for reciters by name.

## Technologies Used
- PHP
- SQLite3 for visitor tracking
- JavaScript for interactive components
- Tailwind CSS for styling
- JSON for data storage

## Project Files
- `index.php`: The main landing page of the website.
- `reciter_details.php`: Provides details for a specific reciter including the list of available suras.
- `Quran.json`: A JSON file containing details of all reciters, including their names, server URLs, and available suras.
- `suras.txt`: Contains the names of all the suras in Arabic.
- `suras_english.txt`: Contains the names of all the suras in English.
- `visitors.db`: SQLite database file for tracking the number of visitors.

## Quran Reciters JSON Structure
The `Quran.json` file contains a list of Quran reciters, each with the following properties:
- `id`: A unique identifier for the reciter.
- `name`: The name of the reciter.
- `Server`: The URL to the server where the reciter's audio files are hosted.
- `rewaya`: The narration method of the recitation.
- `count`: The number of suras recited by the reciter.
- `letter`: The first letter of the reciter's name (used for sorting).
- `suras`: A comma-separated list of suras numbers available for this reciter.

Example:
```json
{
    "id": "4",
    "name": "أبو بكر الشاطري",
    "Server": "http://server11.mp3quran.net/shatri",
    ...
}
```

## Setup Instructions

### Requirements
- PHP server
- SQLite3 extension for PHP

### Installation
1. Clone the repository to your local server directory.
2. Ensure your PHP server is running and has support for SQLite3.
3. Access the `index.php` from your browser to start the application.
4. The site should now be running and accessible.

## Usage
- The main page displays a list of Quran reciters.
- Click on a reciter's name to view and listen to their available suras.
- Use the search bar to find reciters by name.
- The audio player controls allow users to play, pause, and navigate through the recitation tracks.

## License
This project is open-sourced under the MIT License. Feel free to use, modify, and distribute this software as you wish.

## Contribution
Contributions to the website are welcome. Please fork the repository, make your changes, and submit a pull request.

