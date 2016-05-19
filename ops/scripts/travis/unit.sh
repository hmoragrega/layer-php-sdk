#!/bin/bash

bin/phpspec run --format=pretty spec/Api
bin/phpspec run --format=pretty spec/Client*
