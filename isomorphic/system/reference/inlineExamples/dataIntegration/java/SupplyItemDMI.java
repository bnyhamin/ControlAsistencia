//----------------------------------------------------------------------
// Isomorphic SmartClient
//
// SupplyItemDMI implementation
//
//----------------------------------------------------------------------

package com.isomorphic.examples;

import java.util.*;
import javax.servlet.*;
import javax.servlet.http.*;

import com.isomorphic.log.*;
import com.isomorphic.util.*;
import com.isomorphic.datasource.*;

public class SupplyItemDMI {

    Logger log = new Logger(SupplyItemDMI.class.getName());

    public DSResponse fetch(SupplyItem criteria, DSRequest dsRequest) 
        throws Exception 
    {
        log.info("procesing DMI fetch operation");
        DSResponse dsResponse = new DSResponse();
        List matchingItems = SupplyItemStore.findMatchingItems(criteria.getItemID(), 
                                                               criteria.getItemName());
        long startRow = dsRequest.getStartRow();
        long endRow = dsRequest.getEndRow();

        long totalRows = matchingItems.size();
        dsResponse.setTotalRows(totalRows);
        dsResponse.setStartRow(startRow);

        endRow = Math.min(endRow, totalRows);
        dsResponse.setEndRow(endRow);

        List results;
        if (totalRows > 0) {
            results = matchingItems.subList((int)dsResponse.getStartRow(), 
                                                 (int)dsResponse.getEndRow());
        } else {
            results = matchingItems;
        }
        
        dsResponse.setData(results);

        return dsResponse;
    }
 
    public SupplyItem add(SupplyItem record)
        throws Exception
    {
        log.info("procesing DMI add operation");
        SupplyItemStore.storeItem(record);
        return record;
    }


    public SupplyItem update(Map record)
        throws Exception
    {
        log.info("procesing DMI update operation");
        SupplyItem existingRecord = SupplyItemStore.getItemByID((Long)record.get("itemID"));
        DataTools.setProperties(record, existingRecord);
        return existingRecord;
    }


    public SupplyItem remove(SupplyItem record) 
        throws Exception
    {
        log.info("procesing DMI remove operation");
        return SupplyItemStore.removeItem(record.getItemID());
    }
}
