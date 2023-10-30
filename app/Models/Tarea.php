<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;



class Tarea extends Model
{
    use HasFactory;
/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'content',
        'expires_at',
        'status',
    ];

    /**
     * @inheritdoc
     */
    static function deleteRecord(int|string $tareaId) : Model|Exception
    {
        try {
            //Start a new DB transaction
            DB::beginTransaction();

            //Get the desired record
            $tarea = Tarea::findOrFail($tareaId);

            //Delete the record from the DB
            if ($tarea->delete() == 0)
                throw new Exception(__('Notifications/controller.failNewNotification'));

            //if the record was deleted successfully
            DB::commit();

            //Return the deleted record
            return $tarea;
        } catch (Exception $exception) {
            //Rollback all transactions
            DB::rollBack();

            //Throw the exception again
            return $exception;
        }
    }
}
