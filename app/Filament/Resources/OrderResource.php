<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Laravel\Prompts\select;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')
                    ->schema([
                        Select::make('user_id')
                           ->label('Customer')
                           ->relationship('user', 'name')
                           ->searchable()
                           ->preload()
                           ->required(),
                        Select::make('payment_method')
                           ->options([
                            'bkash' => 'Bkash',
                            'rocket' => 'Rocket',
                            'nagad' => 'Nagad',
                            'cod' => 'Cash On Delivery',
                           ]),
                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',])
                            ->default('pending')
                            ->required(),
                        ToggleButtons::make('status')
                            ->inline()
                            ->default('pending')
                            ->required()
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'canceled' => 'Canceled',])
                            ->colors([
                                'pending' => 'info',
                                'processing' => 'primary',
                                'shipped' => 'info',
                                'delivered' => 'success',
                                'canceled' => 'danger',])
                            ->icons([
                                'pending' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'canceled' => 'heroicon-m-x-circle',
                                ]),
                        Select::make('currency')
                            ->options([
                              'bdt' => 'BDT',
                              'inr' => 'INR',
                              'usd' => 'USD',
                              'eur' => 'EUR',
                              'gbp' => 'GBP',
                              'aud' => 'AUD',])
                            ->default('bdt')
                            ->required(),
                        Select::make('shipping_method')
                            ->options([
                                'steadfast' => 'SteadFast',
                                'pathao' => 'Pathao',
                                 ])
                            ->default('steadfast'),
                        Textarea::make('notes')
                            ->columnSpanFull()
                    ])->columns(2),
                Section::make('Order Items')->schema([
                    Repeater::make('items')
                       ->relationship()
                       ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                       ])
                ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
