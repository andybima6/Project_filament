<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use App\Models\category;
use Filament\Forms\Form;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ColorColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ColorPicker;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\RelationManagers;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\ImageColumn;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Posts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // section seperti halnya container
                Section::make('Create a Post')
                    ->description('create posts over here')
                    // ->aside()
                    // ->collapsed()
                    ->schema([
                        // kegunaan dari 'in:it,hi,he' adalah ketika user menginputkan harus ada kata itu
                        // untujk menggunakan validatition itu menggunakan rulus pada ko9denya
                        // ada berbagai macam rules seperti min value, max valuemin leght dan max,numeric ,dll
                        TextInput::make('title')->rules(['min: 2','max:10','in:it,hi,he'])->required(),
                        TextInput::make('slug')->required(),
                        
                        Select::make('category_id')
                            ->label('Category')
                            // kegunaan dari pluck adalah untuk mengambil nilai dari column pd tablenya
                            ->options(category::all()->pluck('name', 'id'))
                            ->required(),
                        ColorPicker::make('color')->required(),
                        // gambarnya terseimpan di public dan berada di storage/app/public
                        MarkdownEditor::make('content')->required()->columnSpan('full'),
                    ])->columnSpan(2)->columns(2),

                Group::make()->schema([
                    Section::make('image')
                        ->collapsed()
                        ->schema([
                            FileUpload::make('thumbnail')->disk('public')->directory('thumbnails'),
                        ])->columnSpan(1),
                    section::make('Meta')->schema([
                        TagsInput::make('tags')->required(),
                        Checkbox::make('published')->required(),
                    ])
                ]),
            ])->columns(3);
        // Responsive
        // ])->columns([
        //     // Ukuran break point, untuk angkanya itu collumnz
        //     'default' => 1,
        //     'md' => 2,
        //     'lg' => 3,
        //     'xl' => 4
        // ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('slug'),
                TextColumn::make('category.name'),
                ColorColumn::make('color'),
                ImageColumn::make('thumbnail'),
                TextColumn::make('content'),
                TextColumn::make('tags'),
                CheckboxColumn::make('published'),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
