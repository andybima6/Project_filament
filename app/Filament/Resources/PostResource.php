<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostsResource\RelationManagers\CommentsRelationManager;
use Filament\Forms;
use App\Models\Post;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use App\Models\category;
use Filament\Forms\Form;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\AuthorsRelationManager;
use Filament\Tables\Filters\Filter;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // digunakan untuk drop-down
    protected static ?string $navigationGroup = 'Blog';

    // Tataletak diatas atau engganya,bisa di cek di resource pada extendsnya
    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'Articels';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Create New post')->tabs([
                    Tab::make('Tab 1')
                        ->icon('heroicon-o-inbox-arrow-down')
                        ->iconPosition(IconPosition::After)
                        ->badge('hi')
                        ->schema([
                        // kegunaan dari 'in:it,hi,he' adalah ketika user menginputkan harus ada kata itu
                        // untuk menggunakan validasi itu menggunakan rules pada kodenya
                        // ada berbagai macam rules seperti min value, max value, min length dan max length, numeric, dll
                        TextInput::make('title')->rules(['min: 2', 'max:10', 'in:it,hi,he'])->required(),
                        TextInput::make('slug')->required(),

                        Select::make('category_id')
                            ->label('Category')
                            // kegunaan dari pluck adalah untuk mengambil nilai dari kolom pada tabelnya
                            // ->options(category::all()->pluck('name', 'id'))
                            ->relationship('category', 'name')
                            ->required(),

                        ColorPicker::make('color')->required(),
                    ]),

                    Tab::make('Content')->schema([
                        MarkdownEditor::make('content')->required()->columnSpan('full'),
                    ]),
                    Tab::make('Meta')->schema([
                        FileUpload::make('thumbnail')->disk('public')->directory('thumbnails'),
                        TagsInput::make('tags')->required(),
                        Checkbox::make('published')->required(),
                    ]),
                ])->columnSpanFull()->activeTab(1)->persistTabInQueryString(),

                // section seperti halnya container

                         //  Section::make('Create a Post')
                            // ->description('create posts over here')
                            // // ->aside()
                            // // ->collapsed()
                            // ->schema([])->columnSpan(2)->column(2),

                            //     Group::make()->schema([
                            //         Section::make('image')
                            //             ->collapsed()
                            //             ->schema([
                            //                   // gambarnya terseimpan di public dan berada di storage/app/public
                            //                 FileUpload::make('thumbnail')->disk('public')->directory('thumbnails'),
                            //             ])->columnSpan(1),



                    // section::make('Meta')->schema([
                    //     TagsInput::make('tags')->required(),
                    //     Checkbox::make('published')->required(),
                    // ]),

                    // section::make('authors')->schema([
                    //     CheckboxList::make('authors')
                    //     ->label('co authors')
                    //     ->searchable()
                    //         //   ->multiple()
                    //         ->relationship('authors', 'name'),
                    // ])

            ])->columns(3);
        // Responsive
        // ])->columns([
        //     // Ukuran break point, untuk angkanya itu columns
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

                TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('slug')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('category.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                ColorColumn::make('color')
                    ->toggleable(),

                ImageColumn::make('thumbnail')
                    ->toggleable(),

                TextColumn::make('content')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tags')
                    ->toggleable(),

                CheckboxColumn::make('published')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Published On')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

            ])
            ->filters([
                // Cara 1 memnetukan filter dengan true and false
                Filter::make('Publish Posts')->query(
                    function (Builder $query) : Builder {
                        return $query->where('published',true);
                    }
                ),
                Filter::make('Unpublish Posts')->query(
                    function (Builder $query) : Builder {
                        return $query->where('published',false);
                    }
                ),
                // cara 2 dijadikan 1 kode
                TernaryFilter::make('published'),

                SelectFilter::make('category_id')
                ->label('Category')
                // cara 1 memanggil relasi
                // ->options(category::all()->pluck('name','id'))
                // cara 2
                ->relationship('category','name')
                ->multiple()
                ->preload()
                ->searchable()
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
            AuthorsRelationManager::class,
            CommentsRelationManager::class
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
