# oauth

GitHub Authentication - Create new application at GitHub
Settings -> Developer Settings -> OAuth Apps -> New OAuth App
Generate Client Secret

Use App Name, Client Id, and Client Secret for configuration




Example 1: php/index.php - PHP using Curl
1. Configure index.php

Homepage URL: http://localhost:8080/php
Authorization callback URL: http://localhost:8080/php


Examples 2: SPRBOOTOUTH - Spring Boot using OAuth2 Client
1. Use https://start.spring.io/
2. Dependencies: Spring Web, Spring Security, OAuth2 Client
3. Select Jar or War for packaging
4. Configure resource/application.yml


HomePage URL: http://localhost:8080
Authorization callback URL: http://localhost:8080/login/oauth2/code/github

If packaging is Jar, 
./mvnw spring-boot:run
