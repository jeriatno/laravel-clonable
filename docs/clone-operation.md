# Clone Operation

The Clone Operation trait allows you to duplicate models and their relationships easily. 

## Usage

1. **Include the Trait in Your Controller**

   ```php
   use App\Traits\Clonable;
   
   class YourController extends Controller
   {
       use Clonable;
   
       ...
   }

2. **Define Routes

   Define routes in your web.php or api.php file to trigger the clone operation:

   ```php
   Route::post('/clones/{id}', [YourController::class, 'clones']);
   Route::post('/bulk-clones', [YourController::class, 'bulkClones']);
   ```

3. **Configure Your Model

   Make sure your model implements the Cloneable interface and defines relationships you want to clone:

   ```php
   use App\Interfaces\WithCloneable;

   class YourModel extends Model implements WithCloneable
   {
      public function getCloneableRelations(): array
      {
          return [
              'relatedModel1',
              'relatedModel2' => function($model) {
                  // Customize attributes if needed
              },
          ];
      }

      public function getCustomCloneData(): array
      {
         // Customize attributes if needed
         return [
             'column1' => 'custom_value',
         ];
      }
   }
   ```
