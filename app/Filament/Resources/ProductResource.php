<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Product;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components;
use App\Jobs\ActivateProductJob;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Resources\ProductResource\Pages\ListProducts;
use App\Filament\Resources\ProductResource\Pages\CreateProduct;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\TextInput::make('name')
                    ->required(),
                Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Components\Select::make('product_category_id')
                    ->relationship('productCategory', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Components\TextInput::make('name')
                            ->required(),
                    ]),
                Components\Select::make('product_color_id')
                    ->relationship('productColor', 'name')
                    ->required(),
                Components\Select::make('vendor_address')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        try {
                            $response = \Illuminate\Support\Facades\Http::withHeaders([
                                "Authorization" => "Bearer " . static::getBearerToken(),
                                "Content-Type" => "application/json",
                                "Accept" => "application/json"
                            ])->post("https://external.asmorphic.com/api/orders/findaddress", [
                                "company_id" => 17,
                                "street_name" => $search,
                                "postcode" => null,
                                "state" => "VIC"
                            ]);

                            if ($response->successful()) {
                                $data = $response->json();
                                // Assuming $data is an array of addresses
                                return collect($data)->pluck('Address', 'Address')->toArray();
                            }
                        } catch (\Exception $e) {
                            // Optionally handle/log error
                        }
                        return [];
                    })
                    ->label('Vendor Address'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
         ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('activate')
                    ->action(function (Product $record) {
                        ActivateProductJob::dispatch($record);
                        
                    })
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name')
                    ->label('Name'),
                Infolists\Components\TextEntry::make('description')
                    ->label('Description'),
                Infolists\Components\TextEntry::make('product_category.name')
                    ->label('Category'),
                Infolists\Components\ViewEntry::make('product_color.hex_code')
                    ->label('Color')
                    ->view('components.product-status-bar')
                    ->viewData([

                        'text' => "Hello",
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TypesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }

    private static function getBearerToken()
    {
        Log::debug('Attempting to get bearer token from external service');
        try {
            $response = Http::post("https://extranet.asmorphic.com/api/login", [
                "email" => "project-test@projecttest.com.au",
                "password" => "oxhyV9NzkZ^02MEB"
            ]);
            Log::debug('Authentication response: ', ['response' => $response->json()]);

            return $response->json()['token'] ?? null;
        } catch (\Exception $e) {
            Notification::make()
                ->title('Connection Error')
                ->body('Could not connect to authentication server')
                ->danger()
                ->send();

            return null;
        }
    }
}
