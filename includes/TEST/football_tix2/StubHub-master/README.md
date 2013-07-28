# StubHub PHP API

A small PHP library for accessing the StubHub API.

For more information about the StubHub API, visit: http://stubhubapi.stubhub.com/

This project is based on the Ruby implementation by 'rloomba': https://github.com/rloomba/stubhub

## Usage

    require_once "./lib/stubhub.php";

    // Search for an event
    $events = Event::search("The Rolling Stones");

    // Search for a venue
    $venues = Venue::search("Arena");

    // Search for a genre
    $genres = Genre:search("Comedy");

    // Get a single event by event id
    $event  = Event::find_event_by_id(1234);

    // Get ticket details for specific event
    $tickets = Event::tickets(1234);

    // Get ticket by ticket ID
    $ticket  = Ticket::find_ticket_by_ticket_id(654321);


## Contribution

This project is not under active development. If your a developer and found this useful, please consider contributing to
and improving this project.


