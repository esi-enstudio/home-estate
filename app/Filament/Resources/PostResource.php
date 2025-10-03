<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationGroup = 'Blog Management';
    protected static ?string $navigationLabel = 'Post';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)->schema([
                    // বাম পাশের মূল কন্টেন্ট (২/৩ অংশ)
                    Forms\Components\Group::make()->schema([
                        Forms\Components\Section::make('পোস্টের বিবরণ')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('শিরোনাম')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\RichEditor::make('body')
                                    ->label('বিস্তারিত')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2),

                    // ডান পাশের সাইডবার (১/৩ অংশ)
                    Forms\Components\Group::make()->schema([
                        Forms\Components\Section::make('ফিচার্ড ছবি')
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('featured_post_image')
                                    ->label('')
                                    ->collection('featured_post_image')
                                    ->image()
                                    ->imageEditor(),
                            ]),

                        Forms\Components\Section::make('স্ট্যাটাস ও সম্পর্ক')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options(['published' => 'Published', 'draft' => 'Draft'])
                                    ->required()
                                    ->default('draft'),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('প্রকাশের তারিখ')
                                    ->default(now()),

                                Forms\Components\Select::make('category_id')
                                    ->label('ক্যাটাগরি')
                                    ->relationship('category', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->required(),

                                Forms\Components\Select::make('user_id')
                                    ->label('লেখক (Author)')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->required()
                                    ->default(auth()->id()),
                            ]),
                    ])->columnSpan(1),
                ]),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('featured_post_image')
                    ->collection('featured_post_image')
                    ->label('ছবি'),

                Tables\Columns\TextColumn::make('title')
                    ->label('শিরোনাম')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('ক্যাটাগরি')
                    ->badge(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('লেখক')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'published',
                        'secondary' => 'draft',
                    ]),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('প্রকাশের তারিখ')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('ক্যাটাগরি')
                    ->preload()
                    ->searchable()
                    ->relationship('category', 'name'),

                Tables\Filters\SelectFilter::make('status')
                    ->options(['published' => 'Published', 'draft' => 'Draft']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('published_at', 'desc');
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
