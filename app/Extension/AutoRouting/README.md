# Sonic Simple AutoRouting Extension

## Usage
1) Add the extension to the `autoload.php`:

```php
use App\Extension\AutoRouting\AutoRouting;

return [
  ...
  
  'extension' => [
      AutoRouting::class,
  ]
];
```

2) Create a controller inside the `app/Controller/_pages` directory.

```php
<?php
namespace App\Controller\_pages; // important

class hello_world extends Sonic\Controller
{
    public function index()
    {
        echo 'Hello world!!';
    }
}
```

3) Visit `/hello-world` to see "Hello world!!".
