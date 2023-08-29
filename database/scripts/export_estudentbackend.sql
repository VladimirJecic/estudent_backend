USE [master]
GO
/****** Object:  Database [estudent_backend]    Script Date: 8/11/2023 9:15:23 AM ******/
CREATE DATABASE [estudent_backend]
 CONTAINMENT = NONE
 ON  PRIMARY 
( NAME = N'estudent_backend', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL15.MSSQLSERVER\MSSQL\DATA\estudent_backend.mdf' , SIZE = 8192KB , MAXSIZE = UNLIMITED, FILEGROWTH = 65536KB )
 LOG ON 
( NAME = N'estudent_backend_log', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL15.MSSQLSERVER\MSSQL\DATA\estudent_backend_log.ldf' , SIZE = 8192KB , MAXSIZE = 2048GB , FILEGROWTH = 65536KB )
 WITH CATALOG_COLLATION = DATABASE_DEFAULT
GO
ALTER DATABASE [estudent_backend] SET COMPATIBILITY_LEVEL = 150
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [estudent_backend].[dbo].[sp_fulltext_database] @action = 'enable'
end
GO
ALTER DATABASE [estudent_backend] SET ANSI_NULL_DEFAULT OFF 
GO
ALTER DATABASE [estudent_backend] SET ANSI_NULLS OFF 
GO
ALTER DATABASE [estudent_backend] SET ANSI_PADDING OFF 
GO
ALTER DATABASE [estudent_backend] SET ANSI_WARNINGS OFF 
GO
ALTER DATABASE [estudent_backend] SET ARITHABORT OFF 
GO
ALTER DATABASE [estudent_backend] SET AUTO_CLOSE OFF 
GO
ALTER DATABASE [estudent_backend] SET AUTO_SHRINK OFF 
GO
ALTER DATABASE [estudent_backend] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [estudent_backend] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO
ALTER DATABASE [estudent_backend] SET CURSOR_DEFAULT  GLOBAL 
GO
ALTER DATABASE [estudent_backend] SET CONCAT_NULL_YIELDS_NULL OFF 
GO
ALTER DATABASE [estudent_backend] SET NUMERIC_ROUNDABORT OFF 
GO
ALTER DATABASE [estudent_backend] SET QUOTED_IDENTIFIER OFF 
GO
ALTER DATABASE [estudent_backend] SET RECURSIVE_TRIGGERS OFF 
GO
ALTER DATABASE [estudent_backend] SET  DISABLE_BROKER 
GO
ALTER DATABASE [estudent_backend] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO
ALTER DATABASE [estudent_backend] SET DATE_CORRELATION_OPTIMIZATION OFF 
GO
ALTER DATABASE [estudent_backend] SET TRUSTWORTHY OFF 
GO
ALTER DATABASE [estudent_backend] SET ALLOW_SNAPSHOT_ISOLATION OFF 
GO
ALTER DATABASE [estudent_backend] SET PARAMETERIZATION SIMPLE 
GO
ALTER DATABASE [estudent_backend] SET READ_COMMITTED_SNAPSHOT OFF 
GO
ALTER DATABASE [estudent_backend] SET HONOR_BROKER_PRIORITY OFF 
GO
ALTER DATABASE [estudent_backend] SET RECOVERY FULL 
GO
ALTER DATABASE [estudent_backend] SET  MULTI_USER 
GO
ALTER DATABASE [estudent_backend] SET PAGE_VERIFY CHECKSUM  
GO
ALTER DATABASE [estudent_backend] SET DB_CHAINING OFF 
GO
ALTER DATABASE [estudent_backend] SET FILESTREAM( NON_TRANSACTED_ACCESS = OFF ) 
GO
ALTER DATABASE [estudent_backend] SET TARGET_RECOVERY_TIME = 60 SECONDS 
GO
ALTER DATABASE [estudent_backend] SET DELAYED_DURABILITY = DISABLED 
GO
ALTER DATABASE [estudent_backend] SET ACCELERATED_DATABASE_RECOVERY = OFF  
GO
EXEC sys.sp_db_vardecimal_storage_format N'estudent_backend', N'ON'
GO
ALTER DATABASE [estudent_backend] SET QUERY_STORE = OFF
GO
USE [estudent_backend]
GO
/****** Object:  User [natasa_spring]    Script Date: 8/11/2023 9:15:23 AM ******/
CREATE USER [natasa_spring] WITHOUT LOGIN WITH DEFAULT_SCHEMA=[dbo]
GO
/****** Object:  User [naca]    Script Date: 8/11/2023 9:15:23 AM ******/
CREATE USER [naca] WITHOUT LOGIN WITH DEFAULT_SCHEMA=[dbo]
GO
/****** Object:  Table [dbo].[course_exams]    Script Date: 8/11/2023 9:15:23 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[course_exams](
	[course_id] [bigint] NOT NULL,
	[exam_period_id] [bigint] NOT NULL,
	[examDateTime] [datetime] NOT NULL,
	[hall] [nvarchar](255) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
 CONSTRAINT [course_exams_course_id_exam_period_id_primary] PRIMARY KEY CLUSTERED 
(
	[course_id] ASC,
	[exam_period_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[course_user]    Script Date: 8/11/2023 9:15:23 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[course_user](
	[user_id] [bigint] NOT NULL,
	[course_id] [bigint] NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[courses]    Script Date: 8/11/2023 9:15:23 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[courses](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[name] [nvarchar](255) NOT NULL,
	[semester] [nvarchar](255) NOT NULL,
	[espb] [int] NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[exam_periods]    Script Date: 8/11/2023 9:15:23 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[exam_periods](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[dateStart] [date] NOT NULL,
	[dateEnd] [date] NOT NULL,
	[name] [nvarchar](255) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[exam_registrations]    Script Date: 8/11/2023 9:15:23 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[exam_registrations](
	[course_id] [bigint] NOT NULL,
	[exam_period_id] [bigint] NOT NULL,
	[student_id] [bigint] NOT NULL,
	[signed_by_id] [bigint] NULL,
	[mark] [int] NOT NULL,
	[comment] [nvarchar](255) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
 CONSTRAINT [exam_registrations_course_id_exam_period_id_student_id_primary] PRIMARY KEY CLUSTERED 
(
	[course_id] ASC,
	[exam_period_id] ASC,
	[student_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[failed_jobs]    Script Date: 8/11/2023 9:15:23 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[failed_jobs](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[uuid] [nvarchar](255) NOT NULL,
	[connection] [nvarchar](max) NOT NULL,
	[queue] [nvarchar](max) NOT NULL,
	[payload] [nvarchar](max) NOT NULL,
	[exception] [nvarchar](max) NOT NULL,
	[failed_at] [datetime] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[migrations]    Script Date: 8/11/2023 9:15:23 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[migrations](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[migration] [nvarchar](255) NOT NULL,
	[batch] [int] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[oauth_access_tokens]    Script Date: 8/11/2023 9:15:23 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[oauth_access_tokens](
	[id] [nvarchar](100) NOT NULL,
	[user_id] [bigint] NULL,
	[client_id] [bigint] NOT NULL,
	[name] [nvarchar](255) NULL,
	[scopes] [nvarchar](max) NULL,
	[revoked] [bit] NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[expires_at] [datetime] NULL,
 CONSTRAINT [oauth_access_tokens_id_primary] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[oauth_auth_codes]    Script Date: 8/11/2023 9:15:23 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[oauth_auth_codes](
	[id] [nvarchar](100) NOT NULL,
	[user_id] [bigint] NOT NULL,
	[client_id] [bigint] NOT NULL,
	[scopes] [nvarchar](max) NULL,
	[revoked] [bit] NOT NULL,
	[expires_at] [datetime] NULL,
 CONSTRAINT [oauth_auth_codes_id_primary] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[oauth_clients]    Script Date: 8/11/2023 9:15:23 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[oauth_clients](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[user_id] [bigint] NULL,
	[name] [nvarchar](255) NOT NULL,
	[secret] [nvarchar](100) NULL,
	[provider] [nvarchar](255) NULL,
	[redirect] [nvarchar](max) NOT NULL,
	[personal_access_client] [bit] NOT NULL,
	[password_client] [bit] NOT NULL,
	[revoked] [bit] NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[oauth_personal_access_clients]    Script Date: 8/11/2023 9:15:23 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[oauth_personal_access_clients](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[client_id] [bigint] NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[oauth_refresh_tokens]    Script Date: 8/11/2023 9:15:23 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[oauth_refresh_tokens](
	[id] [nvarchar](100) NOT NULL,
	[access_token_id] [nvarchar](100) NOT NULL,
	[revoked] [bit] NOT NULL,
	[expires_at] [datetime] NULL,
 CONSTRAINT [oauth_refresh_tokens_id_primary] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[password_reset_tokens]    Script Date: 8/11/2023 9:15:23 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[password_reset_tokens](
	[email] [nvarchar](255) NOT NULL,
	[token] [nvarchar](255) NOT NULL,
	[created_at] [datetime] NULL,
 CONSTRAINT [password_reset_tokens_email_primary] PRIMARY KEY CLUSTERED 
(
	[email] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[personal_access_tokens]    Script Date: 8/11/2023 9:15:23 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[personal_access_tokens](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[tokenable_type] [nvarchar](255) NOT NULL,
	[tokenable_id] [bigint] NOT NULL,
	[name] [nvarchar](255) NOT NULL,
	[token] [nvarchar](64) NOT NULL,
	[abilities] [nvarchar](max) NULL,
	[last_used_at] [datetime] NULL,
	[expires_at] [datetime] NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[users]    Script Date: 8/11/2023 9:15:23 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[users](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[indexNum] [nvarchar](255) NOT NULL,
	[name] [nvarchar](255) NOT NULL,
	[email] [nvarchar](255) NOT NULL,
	[role] [nvarchar](255) NOT NULL,
	[email_verified_at] [datetime] NULL,
	[password] [nvarchar](255) NOT NULL,
	[remember_token] [nvarchar](100) NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [failed_jobs_uuid_unique]    Script Date: 8/11/2023 9:15:23 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [failed_jobs_uuid_unique] ON [dbo].[failed_jobs]
(
	[uuid] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
/****** Object:  Index [oauth_access_tokens_user_id_index]    Script Date: 8/11/2023 9:15:23 AM ******/
CREATE NONCLUSTERED INDEX [oauth_access_tokens_user_id_index] ON [dbo].[oauth_access_tokens]
(
	[user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
/****** Object:  Index [oauth_auth_codes_user_id_index]    Script Date: 8/11/2023 9:15:23 AM ******/
CREATE NONCLUSTERED INDEX [oauth_auth_codes_user_id_index] ON [dbo].[oauth_auth_codes]
(
	[user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
/****** Object:  Index [oauth_clients_user_id_index]    Script Date: 8/11/2023 9:15:23 AM ******/
CREATE NONCLUSTERED INDEX [oauth_clients_user_id_index] ON [dbo].[oauth_clients]
(
	[user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [oauth_refresh_tokens_access_token_id_index]    Script Date: 8/11/2023 9:15:23 AM ******/
CREATE NONCLUSTERED INDEX [oauth_refresh_tokens_access_token_id_index] ON [dbo].[oauth_refresh_tokens]
(
	[access_token_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [personal_access_tokens_token_unique]    Script Date: 8/11/2023 9:15:23 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [personal_access_tokens_token_unique] ON [dbo].[personal_access_tokens]
(
	[token] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [personal_access_tokens_tokenable_type_tokenable_id_index]    Script Date: 8/11/2023 9:15:23 AM ******/
CREATE NONCLUSTERED INDEX [personal_access_tokens_tokenable_type_tokenable_id_index] ON [dbo].[personal_access_tokens]
(
	[tokenable_type] ASC,
	[tokenable_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [users_email_unique]    Script Date: 8/11/2023 9:15:23 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [users_email_unique] ON [dbo].[users]
(
	[email] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [users_indexnum_unique]    Script Date: 8/11/2023 9:15:23 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [users_indexnum_unique] ON [dbo].[users]
(
	[indexNum] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
ALTER TABLE [dbo].[failed_jobs] ADD  DEFAULT (getdate()) FOR [failed_at]
GO
ALTER TABLE [dbo].[users] ADD  DEFAULT ('student') FOR [role]
GO
ALTER TABLE [dbo].[course_exams]  WITH CHECK ADD  CONSTRAINT [course_exams_course_id_foreign] FOREIGN KEY([course_id])
REFERENCES [dbo].[courses] ([id])
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[course_exams] CHECK CONSTRAINT [course_exams_course_id_foreign]
GO
ALTER TABLE [dbo].[course_exams]  WITH CHECK ADD  CONSTRAINT [course_exams_exam_period_id_foreign] FOREIGN KEY([exam_period_id])
REFERENCES [dbo].[exam_periods] ([id])
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[course_exams] CHECK CONSTRAINT [course_exams_exam_period_id_foreign]
GO
ALTER TABLE [dbo].[course_user]  WITH CHECK ADD  CONSTRAINT [course_user_course_id_foreign] FOREIGN KEY([course_id])
REFERENCES [dbo].[courses] ([id])
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[course_user] CHECK CONSTRAINT [course_user_course_id_foreign]
GO
ALTER TABLE [dbo].[course_user]  WITH CHECK ADD  CONSTRAINT [course_user_user_id_foreign] FOREIGN KEY([user_id])
REFERENCES [dbo].[users] ([id])
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[course_user] CHECK CONSTRAINT [course_user_user_id_foreign]
GO
ALTER TABLE [dbo].[exam_registrations]  WITH CHECK ADD  CONSTRAINT [exam_registrations_course_id_exam_period_id_foreign] FOREIGN KEY([course_id], [exam_period_id])
REFERENCES [dbo].[course_exams] ([course_id], [exam_period_id])
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[exam_registrations] CHECK CONSTRAINT [exam_registrations_course_id_exam_period_id_foreign]
GO
ALTER TABLE [dbo].[exam_registrations]  WITH CHECK ADD  CONSTRAINT [exam_registrations_signed_by_id_foreign] FOREIGN KEY([signed_by_id])
REFERENCES [dbo].[users] ([id])
ON UPDATE CASCADE
GO
ALTER TABLE [dbo].[exam_registrations] CHECK CONSTRAINT [exam_registrations_signed_by_id_foreign]
GO
ALTER TABLE [dbo].[exam_registrations]  WITH CHECK ADD  CONSTRAINT [exam_registrations_student_id_foreign] FOREIGN KEY([student_id])
REFERENCES [dbo].[users] ([id])
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[exam_registrations] CHECK CONSTRAINT [exam_registrations_student_id_foreign]
GO
USE [master]
GO
ALTER DATABASE [estudent_backend] SET  READ_WRITE 
GO
