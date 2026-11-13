# Historical Development Wiki: Editorial Notes

These pages are a snapshot of the original Policy Bubble development wiki, written
between November 2017 and March 2021. They were kept as a mix of development journal
and personal diary. The material has been edited for publication in this repository:

- Technical history is kept as written: the project vision, research, architectural
  decisions, implementation notes, debugging sessions and future plans. Original
  dates, page names and the first-person voice are also unchanged.
- Personal diary entries, family and relationship matters, personal habits, notes
  about unrelated employment (employers, clients, colleagues, work hours, timesheets)
  and personal music production notes have been removed.
- Credentials, private email addresses, IP addresses, geolocation coordinates and
  links to private files have been removed or replaced with placeholders such as
  `REDACTED` or `<redacted IP>`.
- Some names of people who suggested or reviewed ideas have been replaced with
  neutral wording (for example, "one suggestion I received").
- Larger removals are marked with an *(Editorial note: ...)*.

Oleg Krivtsov's book *Using Zend Framework 3* was the main technical guide for the
application, and references to it in these pages are kept. See
[../REFERENCES.md](../REFERENCES.md).

## Implemented vs. never implemented

The journal often records ideas next to finished work. The summary below is based on
what the wiki says and a check of the current source code. It is a guide, not an
exhaustive audit.

### Implemented

| Period | Feature |
| --- | --- |
| Nov 2017 | Layout based on the ZF3 skeleton; users, navigation and roles adapted from the ZF3 book samples |
| Dec 2017 | Registration with email confirmation; post ownership permissions (`post.own.edit`, `post.own.delete`); feedback form |
| Dec 2017 | Multiple photos per post using a multi-step form and per-post temp/perm directories |
| Dec 2017 | Responses as posts (post hierarchy), which replaced the comments approach |
| Jan 2018 | Video upload (limited to one video per post) and audio upload |
| Jan–Mar 2018 | PayPal membership tiers and a monthly post limit. **Disabled in March 2018** when the site became free; the code was kept. |
| Feb–Mar 2018 | Deployment to AWS EC2; media storage in S3 with private objects and time-limited URLs; Let's Encrypt SSL |
| Mar 2018 | "My Tags" cloud, tag search, location (geography) search, Analysis page counts, SEO title/description tags, Google Analytics, privacy and disclosure policies |
| Apr 2018 | Change email and close account in settings |
| Apr–May 2018 | Spanish translation, language selector, geolocation-based default language, translated validation messages |
| Jun 2018 | Media titles (stored as S3 metadata) and a post summary/description field |
| Dec 2018 | Analysis page wording changed to public/private counts |
| Apr 2019 | Geolocation moved from geoPlugin to MaxMind GeoIP2 |

### Attempted but abandoned

- Apache Cordova mobile app (May 2018, described as "kind of a flop").
- Restricting direct access to videos through Apache configuration (Feb–Mar 2018).
- Braintree payments (Feb 2018). PayPal was used instead.
- Hosting the site on a home server (Aug 2020 – Mar 2021). The ISP blocked HTTP.

### Ideas never implemented

- User profile pages and unique usernames.
- Data visualizations (d3 charts, maps) beyond the counts on the Analysis page.
- Ratings, "bubble" votes, view counts and Amazon-style reviews.
- Private sharing with invited users or groups, and the "Private Bubble" offshoot for organizations.
- Demographics form, plus "feeling", "citations", "price" and "ethical" fields on posts.
- Excel/data exports.
- Upload progress bar and multiple videos per post.
- A web shop / products-and-services marketplace (Dec 2018 – Mar 2019).
- Re-enabling paid posting / pay-to-post (Dec 2018, Jul 2019).
- A delete confirmation page for posts (Dec 2018).
- A "Save" button on the add/edit post forms (Jul 2018).
- Renaming the site to "Política Burbuja" and software serial numbers (Aug 2018).
