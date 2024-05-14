<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\AuthorsRelationManager;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
//use DeepCopy\Filter\Filter;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\In;
use League\CommonMark\Input\MarkdownInput;
use PharIo\Manifest\Author;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationGroup = 'Blog';


    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    //protected static ?string $navigationGroup = "Blog";
    //protected static ?int $navigationSort = 1;

protected static ?string $modelLabel = 'Articles';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //

Tabs::make('Create New Post')->tabs([
    Tab::make('Tab 1')
    ->icon('heroicon-m-inbox')
    ->iconPosition(IconPosition::After)
    ->badge('Hi')
    ->schema([
        TextInput::make('title')->rules('min:3 , max:10')->required(),
        TextInput::make('slug')->unique(ignoreRecord:true)->required(),
        Select::make('category_id')->options(Category::all()->pluck('name','id'))
                ->label('Category')
                ->required(),
        ColorPicker::make('color')->required(),
    ]),
    
    Tab::make('Content')->schema([
        MarkdownEditor::make('content')->required()->columnSpanFull(),
    ]),


    Tab::make('Meta')->schema([
        FileUpload::make('thumbnail')->disk('public')->directory('thumbnails'),
        TagsInput::make('tags')->required(),
        Checkbox::make('published'),
    ])

])->columnSpanFull()->activeTab(3)->persistTabInQueryString(),

])->columns(3);




    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault:true),
                
                ImageColumn::make('thumbnail')
                ->toggleable(),
                ColorColumn::make('color')
                ->toggleable(),
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
                TextColumn::make('tags')
                ->toggleable(),
                CheckboxColumn::make('published')
                ->toggleable(),
                TextColumn::make('created_at')
                ->label('Published on')
                ->date()
                ->sortable()
                ->searchable()
                ->toggleable(),
            ])
            ->filters([
                //
                Filter::make('Published Posts')->query(
                    function (Builder $query) : Builder {
                      return  $query->where('published', true);
                    }
                ),

        SelectFilter::make('category_id')
        ->label('Category')
        ->relationship('category', 'name')
        ->searchable()
        ->preload()
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

    public static function getRelations(): array
    {
        return [
            //
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
