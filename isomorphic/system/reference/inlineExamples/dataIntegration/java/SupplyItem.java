//----------------------------------------------------------------------
// Isomorphic SmartClient
// Minimal Java server integration example
//
// SupplyItem Bean
//
//    -- provides a simple persistent data structure for this example
//
//----------------------------------------------------------------------

package com.isomorphic.examples;

import java.util.Date;
import java.io.Serializable;

public class SupplyItem implements Serializable {
    protected Long itemID;
    protected String SKU;
    protected String category;
    protected String itemName;
    protected String description;
    protected double unitCost;
    protected String units;
    protected boolean inStock;
    protected Date nextShipmentDate;

    public SupplyItem() { }

    public void setItemID(Long id) { itemID = id; }
    public void setSKU(String sku) { SKU = sku; }
    public void setCategory(String c) { category = c; }
    public void setItemName(String name) { itemName = name; }
    public void setDescription(String d) { description = d; }
    public void setUnitCost(double cost) { unitCost = cost; }
    public void setUnits(String newUnits) { units = newUnits; }
    public void setInStock(boolean val) { inStock = val; }
    public void setNextShipment(Date date) { nextShipmentDate = date; }

    public Long getItemID() { return itemID; }
    public String getSKU() { return SKU; }
    public String getCategory() { return category; }
    public String getItemName() { return itemName; }
    public String getDescription() { return description; }
    public double getUnitCost() { return unitCost; }
    public String getUnits() { return units; }
    public boolean getInStock() { return inStock; }
    public Date getNextShipment() { return nextShipmentDate; }

}

