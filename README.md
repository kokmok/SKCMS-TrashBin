SKCMS-TrashBin
==========

This package is currently under development, more documentation and features will come soon.


# The TrashBin bundle of SKCMS for Symfony2

## Installation

### Download dependecies via composer
use php composer.phar instead of composer on Windows
```
composer require SKCMS/trashbin-bundle:dev-master
```
### Install Languages, Currencies and countries.
Set entity trashBinable
use annotation: 
```
use SKCMS\TrashBinBundle\Annotation\TrashBinable as TrashBinable;
/**
* @ORM\Table()
* @ORM\Entity
* @TrashBinable
*/
class MyEntity{
//...
```
That's all. The reverse method exist but not yet implemented. 

An interface to see trashbinned entities and restore it has to be done quickly.




