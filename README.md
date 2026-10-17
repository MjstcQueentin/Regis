![Régis Logo](https://www.lesmajesticiels.org/assets/logos/smaller/regis.png "Régis")

# Régis Server

Régis Server is a platform developed for [Drama-Tea'c Circus](https://www.dramateaccircus.fr), designed to facilitate the playback of audio and video via a video projector during theatre performances.

It is designed to work with the most basic equipment: all you need is a modern web browser. This means that, regardless of the venue or the device used, the result on stage will be the same.

## Are you looking for the desktop version?

Régis also exists as a standalone application for you to enjoy on your computer. Visit [lesmajesticiels.org/regis](https://www.lesmajesticiels.org/regis) to learn more.

## Deployment

There are two ways to deploy Régis Server.

### Docker

Build a Docker image from the Dockerfile provided in this repository. Useful for quickly getting the app up and running on your infrastructure.

### Manual installation

Deploy a web server that supports PHP 8.4 (or later) with the libxml module.

Clone this repository and copy the files to the root directory.

Ensure that the web server user has write access to the ./projects directory.

## Safety

As things stand, Régis has no security measures in place to prevent unauthorised access. I recommend implementing basic authentication on the web server in order to restrict access to the tool.

## About Drama-Tea'c Circus

We are an English-speaking theatre company based in Besançon, France, whose aim is to promote theatre amongst international students. Every year, we create an original production in all its forms: script, costumes, direction, sound and lighting…

![Logo de Drama-Tea'c Circus](https://www.dramateaccircus.fr/assets/logos/drama-teac-circus-128.png)

## Les Majesticiels

Régis is part of Les Majesticiels, a project from Quentin Pugeat, whose mission is to provide tools making computing easier for everyone.

[![Les Majesticiels](https://www.lesmajesticiels.org/assets/logos/smaller/lesmajesticiels.png "Les Majesticiels")](https://www.lesmajesticiels.org/)
