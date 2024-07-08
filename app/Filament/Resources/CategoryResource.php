<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Str;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use App\Filament\Resources\CategoryResource\RelationManagers\PostRelationManager;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $modelLabel = 'Post Categories';

    protected static ?string $navigationGroup = 'Blog';

    // navigation parant item dilihat berdasarkan nama modellabelnya
    protected static ?string $navigationParentItem = 'Articels';

    protected static ?int $navigationSort = 10;
    protected static bool $shouldSkipAuthorization = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Auto Slug Generation & afterStateUpdated
                TextInput::make('name')->required()
                ->live(onBlur:true)
                ->afterStateUpdated(function(string $operation,string $state,Forms\Set $set,Forms\Get $get,Category $category){
                    // dump($operation); //Edit Create
                    // dump($state);
                    if($operation === 'edit'){
                        return;
                    }
                    $set('slug',str::slug($state));
                    // dump($category);
                }),
                TextInput::make('slug')->required()
                ->minLength(1)
                ->maxLength(150)
                ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('slug'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PostRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
