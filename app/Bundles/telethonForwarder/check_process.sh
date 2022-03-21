#!/bin/bash

if ! pgrep -f 't_forwarder.py'
then
    nohup python3 t_forwarder.py & > test.out
else
    echo "running" > ~/out_test.txt
fi