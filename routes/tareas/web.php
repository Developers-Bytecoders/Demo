<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareasController;

Route::get('/', [TareasController::class, 'index'])->name('tareas.index');

Route::get('tareas/create', [TareasController::class, 'create'])->name('tareas.create');

Route::post('tareas/create', [TareasController::class, 'store'])->name('tareas.store');


/**
 * GET /{locale}/notifications/edit/{id}
 * @param locale string required lang answers. Example: "es" / "en"
 * @param notificationId int required notification slug. Example: "1"
 * Retrieves edit notification form.
 */
Route::get('tareas/edit/{id}', [TareasController::class, 'edit'])->name('tareas.edit');

Route::put('tareas/update/{tareaId}', [TareasController::class, 'update'])->name('tareas.update');
/**
 * DELETE /{locale}/notifications/delete/{notificationId}
 * Delete an notification on notifications table.
 * @param locale string required lang answers. Example: "es" / "en"
 * @param notificationId string required notification id. Example: "1"
 */
Route::delete('tareas/delete/{tareaId}', [TareasController::class, 'destroy'])->name('tareas.delete');
