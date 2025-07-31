<?php

use Illuminate\Support\Facades\Auth;
use App\Livewire\Auth\Login;

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard\Index as DashboardIndex;

use App\Livewire\Role\Index as RoleIndex;

use App\Livewire\Permission\Index as PermissionIndex;

use App\Livewire\Module\Index as ModuleIndex;

use App\Livewire\Inventory\Category\Index as CategoryIndex;

use App\Livewire\Inventory\Product\Index as ProductIndex;
use App\Livewire\Inventory\Product\Create as ProductCreate;
use App\Livewire\Inventory\Product\Edit as ProductEdit;
use App\Livewire\Inventory\Product\View as ProductView;

use App\Livewire\Inventory\Warehouse\Index as WarehouseIndex;
use App\Livewire\Inventory\Warehouse\Create as WarehouseCreate;
use App\Livewire\Inventory\Warehouse\Edit as WarehouseEdit;
use App\Livewire\Inventory\Warehouse\View as WarehouseView;

use App\Livewire\Inventory\Supplier\Index as SupplierIndex;
use App\Livewire\Inventory\Supplier\Create as SupplierCreate;
use App\Livewire\Inventory\Supplier\Edit as SupplierEdit;
use App\Livewire\Inventory\Supplier\View as SupplierView;

use App\Livewire\Inventory\Purchase\Index as PurchaseIndex;
use App\Livewire\Inventory\Purchase\Create as PurchaseCreate;
use App\Livewire\Inventory\Purchase\Edit as PurchaseEdit;
use App\Livewire\Inventory\Purchase\View as PurchaseView;

use App\Livewire\Inventory\Sale\Index as SaleIndex;
use App\Livewire\Inventory\Sale\Create as SaleCreate;
use App\Livewire\Inventory\Sale\Edit as SaleEdit;
use App\Livewire\Inventory\Sale\View as SaleView;

use App\Livewire\User\Student\Index as StudentIndex;
use App\Livewire\User\Student\View as StudentView;

use App\Livewire\Leave\Index as LeaveIndex;
use App\Livewire\Leave\View as LeaveView;

use App\Livewire\Library\Book\Index as BookIndex;
use App\Livewire\Library\Book\Create as BookCreate;
use App\Livewire\Library\Book\Edit as BookEdit;
use App\Livewire\Library\Book\View as BookView;

use App\Livewire\Library\Issue\Index as IssueIndex;
use App\Livewire\Library\Issue\Create as IssueCreate;
use App\Livewire\Library\Issue\Edit as IssueEdit;
use App\Livewire\Library\Issue\View as IssueView;

use App\Livewire\User\Teacher\Index as TeacherIndex;
use App\Livewire\User\Teacher\View as TeacherView;

use App\Livewire\User\Parent\Index as ParentIndex;
use App\Livewire\User\Parent\View as ParentView;

use App\Livewire\User\Employee\Index as EmployeeIndex;
use App\Livewire\User\Employee\View as EmployeeView;

use App\Livewire\Section\Index as SectionIndex;

use App\Livewire\Class\Index as ClassIndex;

use App\Livewire\Designation\Index as DesignationIndex;
use App\Livewire\Activity\Index as ActivityIndex;

use App\Livewire\Announcement\Index as AnnouncementIndex;
// use App\Livewire\Post\Index as PostIndex;

Route::get('/', Login::class)->name('login');

Route::group(['middleware' => ['web','auth','activity']], function () {
    Route::get('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
    
    
    // Route::post('/datatable/posts', [PostIndex::class, 'getDataTableRows'])->name('datatable.posts');
    // Route::get('/posts', PostIndex::class)->name('posts');
    
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
    
    // Activity Log Routes (early placement to override LaravelLogger routes)
    Route::get('/activity-log', ActivityIndex::class)->name('activity-log');
    Route::post('/datatable/activities', [ActivityIndex::class, 'getDataTableRows'])->name('datatable.activities');

    Route::get('/teachers', TeacherIndex::class)->name('teachers');
    Route::post('/datatable/teachers', [TeacherIndex::class, 'getDataTableRows'])->name('datatable.teachers');
    Route::get('/view-teacher/{id}', TeacherView::class)->name('view-teacher');

    Route::get('/roles', RoleIndex::class)->name('roles');
    Route::post('/datatable/roles', [RoleIndex::class, 'getDataTableRows'])->name('datatable.roles');

    Route::get('/permissions', PermissionIndex::class)->name('permissions');
    Route::post('/datatable/permissions', [PermissionIndex::class, 'getDataTableRows'])->name('datatable.permissions');

    Route::get('/modules', ModuleIndex::class)->name('modules');
    Route::post('/datatable/modules', [ModuleIndex::class, 'getDataTableRows'])->name('datatable.modules');

    Route::get('/categories', CategoryIndex::class)->name('categories');

    Route::get('/announcements', AnnouncementIndex::class)->name('announcements');
    Route::post('/datatable/announcements', [AnnouncementIndex::class, 'getDataTableRows'])->name('datatable.announcements');

    Route::get('/products', ProductIndex::class)->name('products');
    Route::get('/add-product', ProductCreate::class)->name('add-product');
    Route::get('/view-product/{id}', ProductView::class)->name('view-product');
    Route::get('/edit-product/{id}', ProductEdit::class)->name('edit-product');

    Route::get('/warehouses', WarehouseIndex::class)->name('warehouses');
    Route::get('/add-warehouse', WarehouseCreate::class)->name('add-warehouse');
    Route::get('/view-warehouse/{id}', WarehouseView::class)->name('view-warehouse');
    Route::get('/edit-warehouse/{id}', WarehouseEdit::class)->name('edit-warehouse');

    Route::get('/suppliers', SupplierIndex::class)->name('suppliers');
    Route::get('/add-supplier', SupplierCreate::class)->name('add-supplier');
    Route::get('/view-supplier/{id}', SupplierView::class)->name('view-supplier');
    Route::get('/edit-supplier/{id}', SupplierEdit::class)->name('edit-supplier');

    Route::get('/purchases', PurchaseIndex::class)->name('purchases');
    Route::get('/add-purchase', PurchaseCreate::class)->name('purchases.create');
    Route::get('/view-purchase/{id}', PurchaseView::class)->name('purchases.view');
    Route::get('/edit-purchase/{id}', PurchaseEdit::class)->name('purchases.edit');

    Route::get('/sales', SaleIndex::class)->name('sales');
    Route::get('/add-sale', SaleCreate::class)->name('sales.create');
    Route::get('/view-sale/{id}', SaleView::class)->name('sales.view');
    Route::get('/edit-sale/{id}', SaleEdit::class)->name('sales.edit');

    Route::get('/students', StudentIndex::class)->name('students');
    Route::post('/datatable/students', [StudentIndex::class, 'getDataTableRows'])->name('datatable.students');
    Route::get('/view-student/{id}', StudentView::class)->name('view-student');

    Route::get('/classes', ClassIndex::class)->name('classes');
    Route::post('/datatable/classes', [ClassIndex::class, 'getDataTableRows'])->name('datatable.classes');

    Route::get('/sections', SectionIndex::class)->name('sections');
    Route::post('/datatable/sections', [SectionIndex::class, 'getDataTableRows'])->name('datatable.sections');

    Route::get('/parents', ParentIndex::class)->name('parents');
    Route::post('/datatable/parents', [ParentIndex::class, 'getDataTableRows'])->name('datatable.parents');
    Route::get('/view-parent/{id}', ParentView::class)->name('view-parent');

    Route::get('/employees', EmployeeIndex::class)->name('employees');
    Route::post('/datatable/employees', [EmployeeIndex::class, 'getDataTableRows'])->name('datatable.employees');
    Route::get('/view-employee/{id}', EmployeeView::class)->name('view-employee');

    Route::get('/designations', DesignationIndex::class)->name('designations');
    Route::post('/datatable/designations', [DesignationIndex::class, 'getDataTableRows'])->name('datatable.designations');

    // Leave Management Routes
    Route::get('/leave', LeaveIndex::class)->name('leave.index');
    Route::post('/datatable/leaves', [LeaveIndex::class, 'getDataTableRows'])->name('datatable.leaves');
    Route::get('/leave/{id}/view', LeaveView::class)->name('leave.view');

    // Books Management Routes
    Route::get('/library/books', BookIndex::class)->name('library.books.index');
    Route::post('/datatable/library/books', [BookIndex::class, 'getDataTableRows'])->name('datatable.library.books');
    Route::get('/library/books/create', BookCreate::class)->name('library.books.create');
    Route::get('/library/books/{id}/edit', BookEdit::class)->name('library.books.edit');
    Route::get('/library/books/{id}/view', BookView::class)->name('library.books.view');

    // Library Issues Management Routes
    Route::get('/library/issues', IssueIndex::class)->name('library.issues.index');
    Route::get('/library/issues/create', IssueCreate::class)->name('library.issues.create');
    Route::get('/library/issues/{issue}/edit', IssueEdit::class)->name('library.issues.edit');
    Route::get('/library/issues/{issue}/view', IssueView::class)->name('library.issues.view');

    // Route::get('/update-password', \App\Livewire\UpdatePassword::class)->name('update-password');
});
