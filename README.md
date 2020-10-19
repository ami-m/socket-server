# socket-server

socket-server is a swoole based PHP tcp socket server.

it exposes several incredibly sophisticated services which were never seen before.

## Installation
 - install [docker](https://docs.docker.com/get-docker/) and [docker-compose](https://docs.docker.com/compose/install/)
 - run `docker-compose up`


## Usage

Use telnet to connect to the running server
```bash
telnet 127.0.0.1 1111
```

Select a command (number) from the menu
```bash
Trying 127.0.0.1...
Connected to 127.0.0.1.
Escape character is '^]'.

---------------
--- Welcome ---
---------------

[1] disk space
[2] ping avg on 8.8.8.8
[3] google top results
[4] exit

Enter:
```

## What's missing

- testing would be nice
- separating the pinging to a different process (maybe even to it's own container)
- better logging
- monitoring for the server state
- cleaner session termination

## Author
[Ami Malimovka](mailto:ami.malimovka@gmail.com)

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)