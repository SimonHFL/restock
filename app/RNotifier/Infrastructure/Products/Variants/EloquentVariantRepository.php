<?php namespace App\RNotifier\Infrastructure\Products\Variants;


use App\RNotifier\Domain\Products\Variants\VariantRepositoryInterface;
use App\RNotifier\Domain\Shops\Shop;

class EloquentVariantRepository implements VariantRepositoryInterface{

    public function firstOrNewByProduct($product, $parameters = [])
    {
        return $product->variants()->firstOrNew($parameters);
    }


    public function delete($id, $shopId)
    {
        $variant = Shop::findOrFail($shopId)
            ->variants()
            ->find($id);


        $variant->delete();

        $this->deleteProductIfNoVariants($variant);
    }

    /**
     * Deletes the product if it does not have any variants saved
     *
     * @param $variant
     */
    public function deleteProductIfNoVariants($variant)
    {
        $product = $variant->product()->first();

        if (!$product->variants()->first()) $product->delete();
    }
}