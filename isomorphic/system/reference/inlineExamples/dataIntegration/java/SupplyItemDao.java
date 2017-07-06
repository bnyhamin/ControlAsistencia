//----------------------------------------------------------------------
// Isomorphic SmartClient
// Minimal Java server integration example
//
// Spring HelloWorld example
//
// Simple invocation of a spring-managed bean directly from the client via RPC DMI
//
//----------------------------------------------------------------------
package com.isomorphic.examples.spring;

import java.io.*;
import java.util.*;

import com.isomorphic.log.Logger;
import com.isomorphic.examples.SupplyItem;
import com.isomorphic.util.DataTools;
import com.isomorphic.datasource.*;
import com.isomorphic.util.ErrorReport;

import org.hibernate.*;
import org.hibernate.criterion.*;

public class SupplyItemDao {

    Logger log = new Logger(SupplyItemDao.class.getName());

    // autoconfigured by Spring
    private SessionFactory sessionFactory;
    public void setSessionFactory(SessionFactory sessionFactory) {
        this.sessionFactory = sessionFactory;
    }

    public DSResponse fetch(DSRequest dsRequest) 
        throws Exception 
    {
        log.info("procesing DMI fetch operation");
        DSResponse dsResponse = new DSResponse();

        Session hibernateSession = sessionFactory.getCurrentSession();

        // DataSource protocol: get filter criteria
        String itemName = (String)dsRequest.getFieldValue("itemName");
                 
        // DataSource protocol: get requested row range
        long startRow = (int)dsRequest.getStartRow();
        long endRow = (int)dsRequest.getEndRow();

        Criteria criteria = hibernateSession.createCriteria(SupplyItem.class);
        Criterion itemNameRestriction = null;
        if (itemName != null) {
            itemNameRestriction = Restrictions.like("itemName", itemName, MatchMode.ANYWHERE);
            criteria.add(itemNameRestriction);
        }
        
        // determine total available rows 
        // this is used by e.g. the ListGrid to auto-size its scrollbar
        criteria.setProjection(Projections.rowCount());
        Integer rowCount = (Integer)criteria.uniqueResult();
        long totalRows = rowCount == null ? 0 : rowCount.intValue();

        // clamp endRow to available rows and slice out requested range
        endRow = Math.min(endRow, totalRows);
                 
        // rebuilt the criteria minus the rowCount projection
        criteria = hibernateSession.createCriteria(SupplyItem.class);                
        if (itemName != null) criteria.add(itemNameRestriction);

        // limit number of rows returned to just what the ListGrid asked for
        criteria.setFirstResult((int)startRow);
        criteria.setMaxResults((int)endRow);
        List matchingItems = criteria.list();

        // DataSource protocol: return matching item beans
        dsResponse.setData(matchingItems);
        // tell client what rows are being returned, and what's available
        dsResponse.setStartRow(startRow);
        dsResponse.setEndRow(endRow);
        dsResponse.setTotalRows(totalRows);        

        return dsResponse;
    }
 
    public DSResponse add(DSRequest dsRequest, SupplyItem item)
        throws Exception
    {
        log.info("procesing DMI add operation");

        DSResponse dsResponse = new DSResponse();

        // perform validation
        ErrorReport errorReport = dsRequest.getDataSource().validate(DataTools.getProperties(item), false);
        if (errorReport != null) {
           dsResponse.setStatus(DSResponse.STATUS_VALIDATION_ERROR);
           dsResponse.setErrorReport(errorReport);
           System.out.println("Errors: " + DataTools.prettyPrint(errorReport));
           return dsResponse;
        }

        
        Session hibernateSession = sessionFactory.getCurrentSession();
        hibernateSession.saveOrUpdate(item);
        dsResponse.setData(item);
        return dsResponse;
    }
    

    public DSResponse update(DSRequest dsRequest, Map newValues)
        throws Exception
    {
        log.info("procesing DMI update operation");

        DSResponse dsResponse = new DSResponse();

        // perform validation
        ErrorReport errorReport = dsRequest.getDataSource().validate(newValues, false);
        if (errorReport != null) {
           dsResponse.setStatus(DSResponse.STATUS_VALIDATION_ERROR);
           dsResponse.setErrorReport(errorReport);
           System.out.println("Errors: " + DataTools.prettyPrint(errorReport));
           return dsResponse;
        }

        // primary key
        Serializable id = (Serializable)dsRequest.getFieldValue("itemID");

        Session hibernateSession = sessionFactory.getCurrentSession();
        SupplyItem item = (SupplyItem)hibernateSession.get(SupplyItem.class, id);

        log.warn("fetched item: " + DataTools.prettyPrint(item));

        // apply new values to the as-saved bean
        DataTools.setProperties(newValues, item);

        log.warn("Saving record: " + DataTools.prettyPrint(item));

        // persist
        hibernateSession.saveOrUpdate(item);
        dsResponse.setData(item);
        return dsResponse;
    }


    public SupplyItem remove(SupplyItem item) 
        throws Exception
    {
        log.info("procesing DMI remove operation");
        
        Session hibernateSession = sessionFactory.getCurrentSession();
        hibernateSession.delete(item);

        return item;
    }
    

}
