# Laravel Microservices Application

## Requirements
- Ensure **Docker** and **Docker Compose** are installed on your machine.

## Overview

This project is composed of **four main services**:

- **Interface**
- **Order Manager**
- **Kitchen**
- **Store**

## Getting Started

To run the application, navigate to the `microservices` directory and execute the following command:

```sh
docker compose up -d --scale orders-queue-create-orders=6
```

The `--scale` parameter is optional and allows you to specify the number of replicas for a particular service. In the example above, the `orders-queue-create-orders` service is scaled to 6 replicas.

The `--build` parameter is used to build the Docker images before starting the containers. It is typically necessary the first time you run the command or when you make changes to your Dockerfile or any related build context. After the initial build, you can omit this parameter to speed up the process and only rebuild when changes are made.

Feel free to adjust the scaling and build options according to your specific needs and development workflow.

## Notes

- This application is intended to be run locally for development and testing purposes.
