//----------------------------------------------------------------------
// Isomorphic SmartClient
//
// SupplyItemFetch implementation
//
//----------------------------------------------------------------------

package com.isomorphic.examples;

import java.util.*;
import javax.servlet.*;
import javax.servlet.http.*;

import com.isomorphic.log.*;
import com.isomorphic.util.*;
import com.isomorphic.datasource.*;



public class SupplyItemFetch {

    public DSResponse fetch(SupplyItem criteria, DSRequest dsRequest) 
        throws Exception 
    {
        DSResponse dsResponse = new DSResponse();
        List matchingItems = SupplyItemStore.findMatchingItems(criteria.getItemID(), 
                                                               criteria.getItemName());
        dsResponse.setData(matchingItems);

        return dsResponse;
    }
}
