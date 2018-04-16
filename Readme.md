# Comskip CUTs Converter


## Description

comskipCutsConverter is a PHP based commandline (CLI) tool to automatically generate comskip information for Enigma2 based set-top-boxes like Dreambox, Vu+ and other OE-Alliance based images (like OpenATV) in their custom .cuts binary format.


## Dependencies

This tool needs, as the name implies, a version of [comskip](https://github.com/erikkaashoek/Comskip) to generate necessary information. 

- comskip 0.82 or higher: The comskip repository is shipped as a submodule in this git repository. Please refer to the Installation chapter to see how to install it.
- PHP 5.6 or higher with CLI enabled: You need a PHP 5.6 CLI SAPI or higher on your machine. Please refer to online manuals on how to install PHP on your operating system.
- Unix/Linux based system (GNU Linux, BSD, macOs), NO Windows support yet.


## Installation

### Installation of dependencies via git clone

- Install PHP 5.6 with CLI, PHAR and XML extension
- [git clone this repository](https://github.com/felixrupp/comskipCutsConverter.git): `git clone --recursive https://github.com/felixrupp/comskipCutsConverter.git`
- Change into the comskipCutsConverter directory: `cd comskipCutsConverter`
- Change into the vendor/erikkaashoek/Comskip subdirectory: `cd vendor/erikkaashoek/Comskip`
- Install comskip as stated in the comskip [https://github.com/erikkaashoek/Comskip](installation manual)

### Build the PHAR archive

After successfully building comskip, you can create your PHAR archive:

    cd ../../../ # Back to our project folder 'comskipCutsConverter'
    php create-phar.php

This creates the build folder with the PHAR file, the comskip executable and a preconfigured comskip.ini file (which can be replaced by your own).


## CLI Usage

    php build/comskipCutsConverter.phar <mpeg2_ts_file> (<mode>)
        
    <mpeg2_ts_file> must be the path to a .ts file
    
    (<mode>) (optional) define the mode of operation.
        'both':     default, which executes comskip
                    and converts the output file to .cuts.
        'comskip':  only execute comskip.
        'convert':  only convert the comskip output to .cuts
                    (you need to run comskip beforehand).


## Future work

I am aiming to do a python version of the tool in near future, to have the possibility to directly run the tool on the set-top-box.


## License

Copyright (c) 2018 Felix Rupp
 
Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be included
in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.