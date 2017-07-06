if exists (select * from sysobjects where id = object_id(N'[dbo].[CA_log_cargas]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[CA_log_cargas]
GO

CREATE TABLE [dbo].[CA_log_cargas] (
	[id_log] [int] IDENTITY (1, 1) NOT NULL ,
	[Incidencia_Codigo] [int] NULL ,
	[fecha] [datetime] NULL ,
	[resultado] [smallint] NULL ,
	[fecha_reg] [datetime] NULL 
)
GO

setuser N'dbo'
GO

EXEC sp_bindefault N'[dbo].[FechaReg]', N'[CA_log_cargas].[fecha_reg]'
GO

setuser
GO

