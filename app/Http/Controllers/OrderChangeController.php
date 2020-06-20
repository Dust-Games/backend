<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrderCreateRequest;
use App\Http\Requests\ChangeOrderRequest;
use App\Models\Currency;
use App\Models\CurrencyAccount;
use App\Models\Order;
use App\Models\OrderChange;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrderChangeController extends Controller
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    protected $currency;

    /**
     * @return \Closure[]
     */
    private function filters()
    {
        return [
            'closed' => function(Builder $query, $value) {
                $query->where('closed', $value === 'true');
            },
        ];
    }

    /**
     * OrderChangeController constructor.
     */
    public function __construct()
    {
        $this->currency = Currency::query()->firstWhere('alias', config('app.default_currency'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return Order::query()->filterRequest($request, $this->filters())->paginate();
    }

    /**
     * @param Order $order
     * @return Order
     */
    public function show(Order $freeOrder)
    {
        return $freeOrder;
    }

    /**
     * @return mixed
     */
    public function meShow()
    {
        return request()->user()->orders()->where('orders.closed', false)->get();
    }

    /**
     * @param ChangeOrderCreateRequest $request
     * @return mixed
     * @throws \Throwable
     */
    public function create(ChangeOrderCreateRequest $request)
    {
        $account = $request
            ->user()
            ->currencyAccounts()
            ->where('currency_id', $this->currency->id)
            ->firstOr(function() use ($request) {
                return CurrencyAccount::query()->create([
                    'currency_id' => $this->currency->id,
                    'balance' => 0,
                    'closed' => false,
                    'owner_id' => $request->user()->id,
                    'owner_type' => User::class,
                ]);
            });

        return OrderChange::createOrder($account, $request->validated());
    }

    /**
     * @param Order $order
     * @param ChangeOrderRequest $request
     * @return mixed
     * @throws \Throwable
     */
    public function credit(Order $order, ChangeOrderRequest $request)
    {
        return OrderChange::credit($order, $request->validated());
    }

    /**
     * @param Order $order
     * @param ChangeOrderRequest $request
     * @return mixed
     * @throws \Throwable
     */
    public function debit(Order $order, ChangeOrderRequest $request)
    {
        return OrderChange::debit($order, $request->validated());
    }

    /**
     * @param Order $order
     * @return mixed
     */
    public function close(Order $order, ChangeOrderRequest $request)
    {
        return OrderChange::closeOrder($order);
    }
}
