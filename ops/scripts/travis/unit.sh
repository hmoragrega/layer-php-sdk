#!/bin/bash

bin/phpspec run --format=pretty spec/Api
bin/phpspec run --format=pretty bin/phpspec run spec/Client*
