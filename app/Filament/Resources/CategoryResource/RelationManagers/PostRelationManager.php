<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostRelationManager extends RelationManager
{
    protected static string $relationship = 'post';

    public function form(Form $form): Form
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
                    TextInput::make('title')->rules(['min: 2', 'max:10'])->required(),
                    TextInput::make('slug')->required(),

                    // Select::make('category_id')
                    //     ->label('Category')
                    //     // kegunaan dari pluck adalah untuk mengambil nilai dari column pd tablenya
                    //     // ->options(category::all()->pluck('name', 'id'))
                    //     ->relationship('category','name')
                    //     ->required(),
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\CheckboxColumn::make('published'),
            ])

            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
